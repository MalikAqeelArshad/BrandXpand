<?php

namespace App;

use Notification;
use App\Traits\MorphTrait;
use App\Traits\FiltersTrait;
use App\Traits\ImageUploadTrait;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ProductNotification;

class Product extends Model
{
    use MorphTrait, ImageUploadTrait, FiltersTrait;
    protected $table = 'products';
    protected $fillable = ['user_id', 'category_id', 'sub_category_id', 'brand_id', 'code', 'name', 'sale', 'discount', 'attrs', 'shipping_cost', 'excerpt', 'description', 'image', 'type', 'publish'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->code = str_pad(mt_rand(1,99999999), 12, 0, STR_PAD_LEFT);
            $product->shipping_cost = request('shipping_cost') ? 1 : 0;
        });
        static::created(function ($product) {
            $users = User::whereHasRole(['administrator', 'admin'])->get()->except(auth()->id());
            Notification::send($users, new ProductNotification($product));
        });
        static::deleted(function ($product) {
            \DB::table('notifications')
            ->where('type', 'App\Notifications\ProductNotification')
            ->where('data', '{"message":"New product added","product_id":'.$product->id.'}')->delete();
            // ->where('data', 'like', '%\"product_id\":'.$product->id.'%')->delete();
        });
    }

    // custom query scope
    public function scopeAllByRole($query)
    {
        return auth()->user()->hasRole(['administrator', 'admin']) ? $query : $query->whereUserId(auth()->id());
    }

    public function scopeWhereLike($query, $columns, $search) {
        $query->where(function($q) use ($columns, $search) {
            foreach(array_wrap($columns) as $column) {
                $q->orWhere($column, 'like', $search);
            }
        });

        return $query;
    }

    public function setAttrsAttribute($value){
        $this->attributes['attrs'] = strtolower($value);
    }

    public function getAttrsAttribute($value){
        return $this->attributes['attrs'] = ucfirst($value);
    }

    public function setDescriptionAttribute($value){
        $this->attributes['description'] = e($value);
    }

    public function getDescriptionAttribute($value)
    {
        return html_entity_decode($value);
        return htmlspecialchars_decode($value);
    }

    public function getDiscountPriceAttribute()
    {
        return round($this->sale * (1 - $this->discount / 100), 2);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function subCategory()
    {
        return $this->belongsTo('App\SubCategory');
    }

    public function reviews()
    {
        return $this->hasMany('App\Review');
    }

    public function stock($status = null)
    {
        return $status ? $this->hasOne('App\ProductStock')->whereStatus($status) : $this->hasOne('App\ProductStock');
    }

    public function stocks($status = null, $attrs = null)
    {
        if ($attrs) {
            return $this->hasMany('App\ProductStock')->whereStatus($status)->whereAttrs($attrs);
        }
        return $status ? $this->hasMany('App\ProductStock')->whereStatus($status) : $this->hasMany('App\ProductStock');
    }

    public function addStock()
    {
        for ($i = 0; $i < request('stock'); $i++) { 
            $this->stocks()->create(request()->all());
        }
        $this->updateStock();
    }

    public function updateStock()
    {
        return $this->stocks('unsold', request('attrs') ?? 'main')->update(['sale'=>request('sale'),'discount'=>request('discount')]);
    }

    public function lastStock($status = 'unsold', $attrs = null)
    {
        if ($attrs) {
            return $this->stocks($status, $attrs)->get()->last() ?: $this->stocks()->whereAttrs($attrs)->get()->last();
        }
        return $this->stocks($status)->get()->last() ?: $this->stocks()->get()->last();
    }

    public function stockIds($attrs, $qty)
    {
        return implode(',', $this->stocks('unsold')->whereAttrs($attrs)->latest()->take($qty)->pluck('id')->toArray());
    }

    public function getStockIds($qty)
    {
        return implode(',', $this->stocks('unsold')->orderBy('id','desc')->take($qty)->pluck('id')->toArray());
    }
}
