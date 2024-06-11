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

class CouponController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Event::all();
        return view('events::coupons::index', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events::coupons::create');
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
            'code' => ['required', 'array'],
            'percentage' => ['required'],
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
        return view('events::coupons::show', ['coupon' => $coupon]);
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
        return view('events::coupons::edit', ['coupon' => $coupon]);
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
            'code' => ['required', 'string', 'max:255'],
            'percentage' => ['required'],
        ];

        $validator = Validator::make($request->all(), $validation_data);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->code = $request->code;
            $coupon->percentage = $request->perccentage;
            $coupon->user_id = $request->user_id;
            $coupon->is_active = $request->is_active ? true : false;
            $coupon->save();

            $entity_name = trans('entities.coupon');
            return redirect('admin/coupons')->with('success', trans('forms.updated_success',['obj' => $entity_name]));
        } catch(\Exception $e) {
            return redirect()
                ->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
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
        $entity_name = trans('entities.coupon');
        return redirect('admin/coupons')->with('success', trans('forms.deleted_success',['obj' => $entity_name]));
    }
}
