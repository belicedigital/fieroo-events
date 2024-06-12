<?php

namespace Fieroo\Events\Controllers;

use Fieroo\Events\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;

class CouponController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Coupon::all();
        return view('events::coupons.index', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('events::coupons.create');
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
            $coupon = Coupon::create([
                'codice' => $request->codice,
                'percentage' => $request->percentage,
                'user_id' => $request->user_id,
                'is_active' => $request->is_active ? true : false,
            ]);

            $entity_name = trans('entities.coupon');
            return redirect('admin/coupons')->with('success', trans('forms.created_success',['obj' => $entity_name]));
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
        $coupon = Coupon::findOrFail($id);
        return view('events::coupons.show', ['coupon' => $coupon]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('events::coupons.edit', ['coupon' => $coupon]);
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
        Coupon::findOrFail($id)->delete();
        $entity_name = trans('entities.coupon');
        return redirect('admin/coupons')->with('success', trans('forms.deleted_success',['obj' => $entity_name]));
    }
}
