<?php

namespace Fieroo\Events\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Fieroo\Events\Models\Event;
use Fieroo\Payment\Models\Payment;
use Fieroo\Exhibitors\Models\Exhibitor;
use Fieroo\Stands\Models\StandsTypeTranslation;
use Fieroo\Events\Models\EventStand;
use Fieroo\Furnitures\Models\Furnishing;
use Fieroo\Payment\Models\Order;
use Fieroo\Bootstrapper\Models\Setting;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Carbon\Carbon;
use Validator;
use DB;
use \stdClass;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EventController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Event::all();
        return view('events::index', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation_data = [
            'stand_type_id' => ['required', 'array'],
            'title' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date_format:Y-m-d', 'before_or_equal:end'],
            'end' => ['required', 'date_format:Y-m-d', 'after_or_equal:start'],
            'subscription_date_open_until' => ['required', 'date_format:Y-m-d', 'before_or_equal:start'],
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            if(!env('UNLIMITED')) {
                // check events limit for subscription
                $request_to_api = Http::get('https://manager-fieroo.belicedigital.com/api/stripe/'.env('CUSTOMER_EMAIL').'/check-limit/max_events');
                if (!$request_to_api->successful()) {
                    throw new \Exception('API Error on get latest subscription '.$request_to_api->body());
                }
                $result_api = $request_to_api->json();
                if(isset($result_api['value']) && Event::all()->count() >= $result_api['value']) {
                    throw new \Exception('Hai superato il limite di Eventi previsti dal tuo piano di abbonamento, per inserire altri Eventi dovrai passare ad un altro piano aumentando il limite di eventi disponibili.');
                }
            }

            $event = Event::create([
                'title' => $request->title,
                'start' => Carbon::parse($request->start)->format('Y-m-d'),
                'end' => Carbon::parse($request->end)->format('Y-m-d'),
                'subscription_date_open_until' => Carbon::parse($request->subscription_date_open_until)->format('Y-m-d'),
                'is_published' => $request->is_published ? true : false,
            ]);

            foreach($request->stand_type_id as $index => $stand_type_id) {
                EventStand::create([
                    'stand_type_id' => $stand_type_id,
                    'event_id' => $event->id,
                ]);
            }

            $entity_name = trans('entities.event');
            return redirect('admin/events')->with('success', trans('forms.created_success',['obj' => $entity_name]));
        } catch(\Exception $e) {
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        $setting = Setting::take(1)->first();
        return view('events::show', ['event' => $event, 'iva' => $setting->iva]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events::edit', ['event' => $event]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validation_data = [
            'title' => ['required', 'string', 'max:255'],
            'start' => ['required', 'date_format:Y-m-d', 'before_or_equal:end'],
            'end' => ['required', 'date_format:Y-m-d', 'after_or_equal:start'],
            'subscription_date_open_until' => ['required', 'date_format:Y-m-d', 'before_or_equal:start'],
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $event = Event::findOrFail($id);
            $event->title = $request->title;
            $event->start = Carbon::parse($request->start)->format('Y-m-d');
            $event->end = Carbon::parse($request->end)->format('Y-m-d');
            $event->subscription_date_open_until = Carbon::parse($request->subscription_date_open_until)->format('Y-m-d');
            $event->is_published = $request->is_published ? true : false;
            $event->save();

            $entity_name = trans('entities.event');
            return redirect('admin/events')->with('success', trans('forms.updated_success',['obj' => $entity_name]));
        } catch(\Exception $e) {
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    private function _subscriptions($event, $user_id, $type = 'subscription', $checkFail = true)
    {
        $query = $event->subscriptions()->where([
            // ['user_id','=', auth()->user()->id],
            ['user_id','=', $user_id],
            ['type_of_payment','=',$type]
        ]);
        return $checkFail ? $query->firstOrFail() : $query->first();
    }

    public function indexFurnishings($event_id)
    {
        $event = Event::findOrFail($event_id);
        $payment_data = self::_subscriptions($event, auth()->user()->id);

        $n_modules = $payment_data->n_modules;
        $stand_type_id = $payment_data->stand_type_id;

        $modules = [];
        for($i = 0; $i < $n_modules; $i++) {
            $obj = new stdClass();
            $obj->id = $i+1;
            $obj->code = '#Mod-'.$i+1;
            array_push($modules, $obj);
        }


        $list = DB::table('furnishings_stands_types')
            ->leftJoin('furnishings_translations', 'furnishings_stands_types.furnishing_id', '=', 'furnishings_translations.furnishing_id')
            ->leftJoin('furnishings', 'furnishings_stands_types.furnishing_id', 'furnishings.id')
            ->where([
                ['furnishings_translations.locale', '=', App::getLocale()],
                ['furnishings_stands_types.stand_type_id', '=', $stand_type_id],
                ['furnishings.is_variant', '=', 0]
            ])
            ->select('furnishings.*', 'furnishings_translations.description', 'furnishings_stands_types.is_supplied', 'furnishings_stands_types.min', 'furnishings_stands_types.max')
            ->orderBy('furnishings_stands_types.is_supplied', 'DESC')
            ->orderBy('furnishings_stands_types.min', 'ASC')
            ->get();

        foreach($list as $l) {
            $l->variants = Furnishing::where('variant_id', '=', $l->id)->get();
        }

        $stand_trans = StandsTypeTranslation::where([
            ['locale','=',App::getLocale()],
            ['stand_type_id','=',$stand_type_id],
        ])->firstOrFail();

        $setting = Setting::take(1)->first();

        return view('events::furnishings', [
            'event_id' => $event->id,
            'list' => $list,
            'modules' => $modules,
            'exhibitor_data' => auth()->user()->exhibitor->detail,
            'stand_type_id' => $stand_type_id,
            'stand_name' => $stand_trans->name,
            'iva' => $setting->iva
        ]);
    }

    public function recapFurnishings($event_id, $exhibitor_id)
    {
        $event = Event::findOrFail($event_id);
        $exhibitor = Exhibitor::findOrFail($exhibitor_id);

        $orders = Order::where([
            ['orders.exhibitor_id','=',$exhibitor_id],
            ['orders.event_id','=',$event_id],
        ])->get();
        foreach($orders as $order) {
            $order->description = $order->furnishing->is_variant ? $order->furnishing->parent->translations()->where('locale',App::getLocale())->first()->description : $order->furnishing->translations()->where('locale',App::getLocale())->first()->description;
        }

        $payment_data = self::_subscriptions($event, $exhibitor->user->id);

        $extra_furnishings_payment = self::_subscriptions($event, $exhibitor->user->id, 'furnishing', false);
        $extra = 0;
        if(is_object($extra_furnishings_payment)) {
            $extra = $extra_furnishings_payment->amount;
        }

        $n_modules = $payment_data->n_modules;
        $amount = $payment_data->amount;

        $stand_trans = StandsTypeTranslation::where([
            ['locale','=',App::getLocale()],
            ['stand_type_id','=',$payment_data->stand_type_id],
        ])->firstOrFail();

        $setting = Setting::take(1)->first();

        return view('events::recap', [
            'extra' => $extra,
            'orders' => $orders,
            'n_modules' => $n_modules,
            'amount' => $amount,
            'stand_name' => $stand_trans->name,
            'back_url' => 'admin/dashboard',
            'iva' => $setting->iva
        ]);
    }

    public function indexExhibitors($event_id)
    {
        $event = Event::findOrFail($event_id);
        $subscriptions = Payment::where([
            ['event_id', '=', $event_id],
            ['type_of_payment', '=', 'subscription'],
        ])->get();
        return view('events::subscriptions', ['id' => $event_id, 'list' => $subscriptions, 'event_title' => $event->title]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Event::findOrFail($id)->delete();
        $entity_name = trans('entities.event');
        return redirect('admin/events')->with('success', trans('forms.deleted_success',['obj' => $entity_name]));
    }

    public function getStandSelectList($event_id)
    {
        $response = [
            'status' => false
        ];

        try {
            $event = Event::findOrFail($event_id);

            $query = StandsTypeTranslation::where('locale','=',auth()->user()->exhibitor->locale);
            if($event->stands()->count() > 0) {
                $query = $query->whereIn('stand_type_id', $event->stands->pluck('stand_type_id'));
            }
            $response['status'] = true;
            $response['data'] = $query->get();
            return response()->json($response);
        } catch(\Exception $e){
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function exportEventsExhibitors()
    {
        $list = DB::table('payments')
            ->leftJoin('users', 'payments.user_id', '=', 'users.id')
            ->leftJoin('events', 'payments.event_id', '=', 'events.id')
            ->leftJoin('exhibitors_data', 'users.email', 'exhibitors_data.email_responsible')
            ->where('payments.type_of_payment', '=', 'subscription')
            ->select(
                'events.title',
                'events.start',
                'events.end',
                'users.email',
                'exhibitors_data.*', 
            )
            ->get();
            
        $to_export = [];
        foreach($list as $l) {
            $item = [
                'evento' => $l->title,
                'data inizio evento' => $l->start,
                'data fine evento' => $l->end,
                'nome o ragione sociale' => $l->company,
                'indirizzo' => $l->address,
                'civico' => $l->civic_number,
                'città' => $l->city,
                'cap' => (string) $l->cap,
                'provincia' => $l->province,
                'telefono' => $l->phone,
                'sito web' => $l->web,
                'responsabile' => $l->responsible,
                'e-mail' => $l->email,
                'telefono responsabile' => $l->phone_responsible,
                'codice fiscale' => $l->fiscal_code,
                'partita iva' => (string) $l->vat_number,
                'codice univoco' => (string) $l->uni_code,
                'dati fatturazione diversi' => $l->diff_billing ? 'si' : 'no',
                'indirizzo fatturazione' => $l->receiver_address,
                'civico fatturazione' => $l->receiver_civic_number,
                'città fatturazione' => $l->receiver_city,
                'cap fatturazione' => (string) $l->receiver_cap,
                'provincia fatturazione' => $l->receiver_province,
                'codice fiscale fatturazione' => $l->receiver_fiscal_code,
                'partita iva fatturazione' => (string) $l->receiver_vat_number,
                'codice univoco fatturazione' => (string) $l->receiver_uni_code,
            ];
            array_push($to_export, $item);
        }

        $csv = SimpleExcelWriter::streamDownload('Eventi_Espositori.csv')
            ->addHeader(array_keys($to_export[0]))
            ->addRows($to_export);

        return $csv->toBrowser();
    }

    public function exportEventExhibitors($event_id)
    {
        $list = DB::table('payments')
            ->leftJoin('users', 'payments.user_id', '=', 'users.id')
            ->leftJoin('exhibitors_data', 'users.email', 'exhibitors_data.email_responsible')
            ->leftJoin('stands_types_translations', 'payments.stand_type_id', '=', 'stands_types_translations.stand_type_id')
            ->where([
                ['payments.type_of_payment', '=', 'subscription'],
                ['payments.event_id', '=', $event_id],
                ['stands_types_translations.locale', '=', 'it'],
            ])
            ->select(
                'users.email',
                'exhibitors_data.*', 
                'payments.amount',
                'payments.n_modules',
                'stands_types_translations.name as stand',
            )
            ->get();
            
        $to_export = [];
        foreach($list as $l) {
            $item = [
                'nome o ragione sociale' => $l->company,
                'indirizzo' => $l->address,
                'civico' => $l->civic_number,
                'città' => $l->city,
                'cap' => (string) $l->cap,
                'provincia' => $l->province,
                'telefono' => $l->phone,
                'sito web' => $l->web,
                'responsabile' => $l->responsible,
                'e-mail' => $l->email,
                'telefono responsabile' => $l->phone_responsible,
                'codice fiscale' => $l->fiscal_code,
                'partita iva' => (string) $l->vat_number,
                'codice univoco' => (string) $l->uni_code,
                'dati fatturazione diversi' => $l->diff_billing ? 'si' : 'no',
                'indirizzo fatturazione' => $l->receiver_address,
                'civico fatturazione' => $l->receiver_civic_number,
                'città fatturazione' => $l->receiver_city,
                'cap fatturazione' => (string) $l->receiver_cap,
                'provincia fatturazione' => $l->receiver_province,
                'codice fiscale fatturazione' => $l->receiver_fiscal_code,
                'partita iva fatturazione' => (string) $l->receiver_vat_number,
                'codice univoco fatturazione' => (string) $l->receiver_uni_code,
                'importo' => $l->amount,
                'stand' => $l->stand,
                'n. moduli' => $l->n_modules,
            ];
            array_push($to_export, $item);
        }

        $event = Event::find($event_id);
        $csv = SimpleExcelWriter::streamDownload(Str::snake($event->title).'_Espositori.csv')
            ->addHeader(array_keys($to_export[0]))
            ->addRows($to_export);

        return $csv->toBrowser();
    }
}
