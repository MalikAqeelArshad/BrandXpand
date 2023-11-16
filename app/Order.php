<?php

namespace App;

use App\ProductStock;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['user_id', 'product_id','product_stock_ids', 'coupon_id', 'reference_number', 'payment_method', 'shipping_charges','total','grand_total', 'status'];
    protected $dates = ['shipped_date','delivered_date'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function stocks($ids)
    {
        return count($ids) ? ProductStock::whereIn('id', $ids)->get() : $this->belongsToMany('App\ProductStock');
    }

    public function addresses()
    {
        return $this->morphMany('App\Address','addressable');
    }

    public function address()
    {
        return $this->morphOne('App\Address','addressable');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    public function review()
    {
        return $this->hasOne('App\Review');
    }

    public function coupon()
    {
        return $this->belongsTo('App\Coupon');
    }

    // custom query scope
    public function scopeStatus($query, $status)
    {
        return $status ? $query->whereStatus($status) : $query;

        /*if (empty($status)) { return $query->where('status','pending')->orwhere('status','shipped')->orwhere('status','delivered'); }
        switch ($status) {
            case 'pending':
                return $query->where('status','=','pending');
            case 'shipped':
                return $query->where('status','=','shipped');
            case 'delivered':
                return $query->where('status','=','delivered');
            case 'cancelled':
                return $query->where('status','=','cancelled');
            default:
                return abort(404);
        }*/
    }
}
