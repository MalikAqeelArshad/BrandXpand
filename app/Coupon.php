<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';
    protected $fillable = ['user_id', 'code', 'name', 'discount', 'expiry_date', 'status'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($coupon) {
            $coupon->code = str_pad(mt_rand(1,99999999), 12, 0, STR_PAD_LEFT);
        });
        // static::created(function ($coupon) {
        //     $coupon->save();
        // });
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
