<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    protected $fillable = ['addressable_type', 'addressable_id', 'type', 'address', 'mobile' , 'city', 'state', 'country', 'postcode'];

	/**
     * Get all of the owning addressable models.
     */
    public function addressable()
    {
        return $this->morphTo('addressable');
    }
}
