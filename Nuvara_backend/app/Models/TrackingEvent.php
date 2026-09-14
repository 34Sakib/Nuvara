<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TrackingEvent extends Model { protected $fillable=['order_id','status','title','description','location','occurred_at']; protected $casts=['occurred_at'=>'datetime']; }
