<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class InventoryMovement extends Model { protected $fillable = ['product_id','variant_id','quantity','reason','reference','actor']; public function product() { return $this->belongsTo(Product::class); } }
