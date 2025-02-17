<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
   protected $fillable = [
    'user_id',
    'street',
    'building',
    'area'
   ];
   public function user(){
    return $this->belongsTo(User::class, 'user_id');
   }
}
