<?php

namespace App;

use App\Traits\HasRolesTrait;
use App\Traits\MorphTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes, MorphTrait, HasRolesTrait;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime'];

    // Hash a password before saving
    protected function setPasswordAttribute($value)
    {
        // check if the value is already a hash (Regex: String begins with '$2y$##$' followed by at least 50 characters)
        if ( preg_match('/^\$2y\$[0-9]*\$.{50,}$/', $value) ) {
            // if it is so, set the attribute without hashing again
            $this->attributes['password'] = $value;
        }
        else {
            // else hash the password and set as attribute
            $this->attributes['password'] = bcrypt($value);
        }
    }

    // custom query scope
    public function scopeStatus($query, $status)
    {
        if (empty($status)) { return $query->withTrashed(); }
        switch ($status) {
            case 'deactive':
            return $query->onlyTrashed();
            case 'pending':
            return $query->whereNull('email_verified_at');
            case 'active':
            return $query->whereNotNull('email_verified_at');
            default:
            return abort(404);
        }
    }

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function sliders()
    {
        return $this->hasMany('App\Slider');
    }

    public function slides()
    {
        return $this->hasMany('App\Slide');
    }

    public function videos()
    {
        return $this->hasMany('App\Video');
    }

    public function brands()
    {
        return $this->hasMany('App\Brand');
    }

    public function logos()
    {
        return $this->hasMany('App\Logo');
    }

    public function categories()
    {
        return $this->hasMany('App\Category');
    }

    public function subCategories()
    {
        return $this->hasMany('App\SubCategory');
    }

    public function coupons()
    {
        return $this->hasMany('App\Coupon');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function shippingCosts()
    {
        return $this->hasMany('App\ShippingCost');
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function avatar()
    {
        return $this->morphOne('App\Gallery', 'imageable');
    }

}
