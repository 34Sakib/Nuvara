<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Refund extends Model { protected $fillable = ['order_id','refund_number','amount','reason','restock_status','actor']; protected $casts = ['amount'=>'float']; }
