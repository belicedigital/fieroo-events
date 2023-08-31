<?php

function userEventIsNotSubscribed($user_id, $event_id) {
    $user_event_subscription = \DB::table('payments')->where([
        ['user_id', '=', $user_id],
        ['event_id', '=', $event_id],
        ['type_of_payment', '=', 'subscription']
    ])->first();
    if(!is_object($user_event_subscription)) {
        return true;
    }
    return false;
}

function userEventIsNotFurnished($user_id, $event_id, $exhibitor_id) {
    $user_event_furnished = \DB::table('payments')->where([
        ['user_id', '=', $user_id],
        ['event_id', '=', $event_id],
        ['type_of_payment', '=', 'furnishing']
    ])->first();

    $count_user_orders = \DB::table('orders')->where([
        ['exhibitor_id', '=', $exhibitor_id],
        ['event_id', '=', $event_id],
    ])->count();

    if(!userEventIsNotSubscribed($user_id, $event_id) && (!is_object($user_event_furnished) && $count_user_orders <= 0)) {
        return true;
    }
    return false;
}

function getFurnishingImg($furnishing_id) {
    $furnishing = \DB::table('furnishings')->where('id','=',$furnishing_id)->first();
    // return $furnishing->file_path;
    if(is_object($furnishing) && strlen($furnishing->file_path) > 0) {
        return asset('img/furnishings/'.$furnishing->file_path);
    }
    return asset('img/default-150x150.png');
}