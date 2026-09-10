<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\{RegisterSession, InventoryMovement, Refund, Order, Product};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class OperationsController extends Controller {
    public function openRegister(Request $request) { $data=$request->validate(['register_code'=>'required|string|max:40','cashier_name'=>'required|string|max:120','opening_float'=>'required|numeric|min:0']); if(RegisterSession::where('register_code',$data['register_code'])->where('status','open')->exists()) return response()->json(['message'=>'This register is already open.'],409); return response()->json(RegisterSession::create([...$data,'opened_at'=>now()])); }
    public function closeRegister(Request $request, RegisterSession $session) { abort_if($session->status !== 'open',409,'Register session is already closed.'); $data=$request->validate(['closing_cash'=>'required|numeric|min:0']); $sales=Order::where('channel','pos')->where('status','completed')->whereBetween('created_at',[$session->opened_at,now()])->sum('total'); $session->update(['closing_cash'=>$data['closing_cash'],'expected_cash'=>$session->opening_float+$sales,'variance'=>$data['closing_cash']-$session->opening_float-$sales,'status'=>'closed','closed_at'=>now()]); return response()->json($session); }
    public function movements(Request $request) { return response()->json(InventoryMovement::with('product')->latest()->paginate($request->integer('limit',25))); }
    public function adjustStock(Request $request) { $data=$request->validate(['product_id'=>'required|exists:products,id','variant_id'=>'nullable|exists:product_variants,id','quantity'=>'required|integer|between:-100000,100000','reason'=>'required|string|max:120','actor'=>'nullable|string|max:120']); $movement=DB::transaction(function() use($data){ $product=Product::lockForUpdate()->findOrFail($data['product_id']); $next=$product->stock+$data['quantity']; abort_if($next<0,422,'Stock cannot become negative.'); $product->update(['stock'=>$next]); return InventoryMovement::create($data); }); return response()->json($movement,201); }
    public function refund(Request $request, Order $order) { $data=$request->validate(['amount'=>'required|numeric|min:0.01','reason'=>'required|string|max:255','restock_status'=>'required|in:restocked,not_restocked','actor'=>'nullable|string|max:120']); abort_if($data['amount']>$order->total,422,'Refund exceeds the order total.'); $refund=Refund::create([...$data,'order_id'=>$order->id,'refund_number'=>'RF-'.strtoupper(Str::random(10))]); $order->update(['payment_status'=>'refunded']); return response()->json($refund,201); }
}
