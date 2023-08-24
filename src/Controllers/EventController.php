<?php

namespace Fieroo\Events\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Fieroo\Events\Models\Event;
// use App\Models\Order;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;
use DB;
use \stdClass;

class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

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

    // public function subscription($id)
    // {
    //     // check if user logged has payment info
    //     // subscribe user logged to the event $id
    // }

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

    public function indexFurnishings($event_id)
    {
        $payment_data = DB::table('payments')->where([
            ['event_id','=',$event_id],
            ['user_id','=',auth()->user()->id],
            ['type_of_payment','=','subscription']
        ])->first();
        if(!is_object($payment_data)) {
            abort(404);
        }
        $n_modules = $payment_data->n_modules;
        $stand_type_id = $payment_data->stand_type_id;

        $exhibitor = DB::table('exhibitors')->where('user_id','=',auth()->user()->id)->first();
        if(!is_object($exhibitor)) {
            abort(404);
        }
        $exhibitor_data = DB::table('exhibitors_data')
            ->where('exhibitor_id','=',$exhibitor->id)
            ->first();
        if(!is_object($exhibitor_data)) {
            abort(404);
        }

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
            $l->variants = DB::table('furnishings')->where('variant_id', '=', $l->id)->get();
        }

        $stand_trans = DB::table('stands_types_translations')->where([
            ['locale','=',App::getLocale()],
            ['stand_type_id','=',$stand_type_id],
        ])->first();
        if(!is_object($stand_trans)) {
            abort(404);
        }
        $stand_name = $stand_trans->name;
        
        return view('events::furnishings', ['event_id' => $event_id,'list' => $list, 'modules' => $modules, 'exhibitor_data' => $exhibitor_data, 'stand_type_id' => $stand_type_id, 'stand_name' => $stand_name]);
    }

    public function recapFurnishings($event_id)
    {
        $orders = DB::table('orders')
            ->leftJoin('furnishings_translations','orders.furnishing_id','=','furnishings_translations.furnishing_id')
            ->where([
                ['orders.exhibitor_id','=',auth()->user()->exhibitor->id],
                ['orders.event_id','=',$event_id],
                ['furnishings_translations.locale','=',App::getLocale()]
            ])
            ->select('orders.furnishing_id', DB::raw('sum(qty) as qty'), DB::raw('sum(price) as price'), 'furnishings_translations.description')
            ->groupBy('orders.furnishing_id','furnishings_translations.description')
            ->get();

        $payment_data = DB::table('payments')->where([
            ['event_id','=',$event_id],
            ['user_id','=',auth()->user()->id],
            ['type_of_payment','=','subscription']
        ])->first();
        if(!is_object($payment_data)) {
            abort(404);
        }

        $extra_furnishings_payment = DB::table('payments')->where([
            ['event_id','=',$event_id],
            ['user_id','=',auth()->user()->id],
            ['type_of_payment','=','furnishing']
        ])->first();
        $extra = 0;
        if(is_object($extra_furnishings_payment)) {
            $extra = $extra_furnishings_payment->amount;
        }

        $n_modules = $payment_data->n_modules;
        $amount = $payment_data->amount;

        $stand_trans = DB::table('stands_types_translations')->where([
            ['locale','=',App::getLocale()],
            ['stand_type_id','=',$payment_data->stand_type_id],
        ])->first();
        if(!is_object($stand_trans)) {
            abort(404);
        }

        return view('events::recap', [
            'extra' => $extra,
            'orders' => $orders,
            'n_modules' => $n_modules,
            'amount' => $amount,
            'stand_name' => $stand_trans->name
        ]);
    }

    public function indexExhibitors($event_id)
    {
        $event = Event::findOrFail($event_id);
        $subscriptions = Event::where('id', '=', $event_id)
            ->whereHas('subscriptions', function($q) {
                $q->where('type_of_payment', 'subscription');
            })->get();
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