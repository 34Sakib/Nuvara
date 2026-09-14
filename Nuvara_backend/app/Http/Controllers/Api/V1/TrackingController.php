<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\{Order, TrackingEvent};
use Illuminate\Http\Request;
class TrackingController extends Controller {
 public function track(Request $request) { $data=$request->validate(['order_number'=>'required|string|max:80','email'=>'required|email|max:255']); $order=Order::where('order_number',$data['order_number'])->where('customer_email',$data['email'])->first(); if(!$order) return response()->json(['message'=>'We could not find an order with those details.'],404); $events=TrackingEvent::where('order_id',$order->id)->orderBy('occurred_at')->get(); return response()->json(['order_number'=>$order->order_number,'status'=>$order->status,'payment_status'=>$order->payment_status,'currency'=>$order->currency,'total'=>$order->total,'shipping_name'=>$order->shipping_name,'shipping_city'=>$order->shipping_city,'shipping_country'=>$order->shipping_country,'events'=>$events]); }
}
