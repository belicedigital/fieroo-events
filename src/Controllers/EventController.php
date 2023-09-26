<?php

namespace Fieroo\Events\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Fieroo\Events\Models\Event;
use Fieroo\Payment\Models\Payment;
use Fieroo\Exhibitors\Models\Exhibitor;
use Fieroo\Stands\Models\StandsTypeTranslation;
use Fieroo\Furnitures\Models\Furnishing;
use Fieroo\Payment\Models\Order;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;
use DB;
use \stdClass;

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
            $event = Event::create([
                'title' => $request->title,
                'start' => Carbon::parse($request->start)->format('Y-m-d'),
                'end' => Carbon::parse($request->end)->format('Y-m-d'),
                'subscription_date_open_until' => Carbon::parse($request->subscription_date_open_until)->format('Y-m-d'),
                'is_published' => $request->is_published ? true : false,
            ]);

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
        return view('events::show', ['event' => $event]);
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
        
        return view('events::furnishings', [
            'event_id' => $event->id,
            'list' => $list, 
            'modules' => $modules, 
            'exhibitor_data' => auth()->user()->exhibitor->detail,
            // 'exhibitor_data' => $event->user->exhibitor->detail,
            'stand_type_id' => $stand_type_id, 
            'stand_name' => $stand_trans->name
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

        return view('events::recap', [
            'extra' => $extra,
            'orders' => $orders,
            'n_modules' => $n_modules,
            'amount' => $amount,
            'stand_name' => $stand_trans->name,
            'back_url' => 'admin/dashboard',
        ]);
    }

    public function indexExhibitors($event_id)
    {
        $event = Event::findOrFail($event_id);
        $subscriptions = Payment::where([
            ['event_id', '=', $event_id],
            ['type_of_payment', '=', 'subscription'],
        ])->get();
        return view('events::subscriptions', ['list' => $subscriptions, 'event_title' => $event->title]);
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
}