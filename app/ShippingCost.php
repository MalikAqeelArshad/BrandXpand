<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    protected $table = 'shipping_costs';
    protected $fillable = ['user_id', 'country', 'city', 'state', 'charges'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
