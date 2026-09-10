<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RegisterSession extends Model { protected $fillable = ['register_code','cashier_name','opening_float','closing_cash','expected_cash','variance','status','opened_at','closed_at']; protected $casts = ['opening_float'=>'float','closing_cash'=>'float','expected_cash'=>'float','variance'=>'float','opened_at'=>'datetime','closed_at'=>'datetime']; }
