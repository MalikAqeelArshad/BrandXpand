<?php

namespace App;

use App\User;
use App\Product;
use App\Traits\FiltersTrait;
use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use FiltersTrait;
    protected $table = 'product_stocks';
    protected $fillable = ['product_id', 'attrs', 'purchase', 'sale', 'discount', 'status'];

    public function setAttrsAttribute($value){
        $this->attributes['attrs'] = strtolower($value);
    }

    public function getAttrsAttribute($value){
        return $this->attributes['attrs'] = ucfirst($value);
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function getDiscountPriceAttribute()
    {
        return round($this->sale * (1 - $this->discount / 100), 2);
    }

    public function getProfitAttribute()
    {
        return $this->discount_price - $this->purchase;
    }

    public function scopeUniqueAttrs($query)
    {
        return $query->pluck('attrs')->unique();
        // return $query->select('attrs')->distinct()->pluck('attrs');
    }

    public function scopeUniquePurchase($query, $attrs)
    {
        return $query->whereAttrs($attrs)->pluck('purchase')->unique();
        // return $query->whereAttrs($attrs)->latest()->select('purchase')->distinct()->pluck('purchase');
    }

    public function scopeDistinctDate($query, $attrs, $purchase)
    {
        return $query->whereAttrs($attrs)->wherePurchase($purchase)->latest()
        ->select(\DB::raw('DATE(created_at) as created'))->distinct()->pluck('created');
    }

    public function scopeStockCount($query, $attrs, $purchase, $date)
    {
        return $query->whereAttrs($attrs)->wherePurchase($purchase)->whereDate('created_at', $date)->count();
    }
}
