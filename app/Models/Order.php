<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class Order extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';
    protected $fillable = [
        "quantity",
        "device_name",
        "price",
        "profit",
        "credit_before",
        "credit_after",
        "product_id",
        "order_status_id",
        "userable_type",
        "userable_id",
    ];

    public function setUserableTypeAttribute($value)
    {
        if (request("userable_type")) {
            $this->attributes["userable_type"] = request("userable_type");
        }
        $this->attributes["userable_type"] = $value;
    }

    public function setUserableIdAttribute($value)
    {
        if (request("userable")) {
            $this->attributes["userable_id"] = request("userable");
        }
        $this->attributes["userable_id"] = $value;
    }

    public function userable()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function orderPrepaidCardStocks()
    {
        return $this->hasMany(OrderPrepaidCardStock::class);
    }

    public function getPriceAttribute($value)
    {
        return round($value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setPriceAttribute($value)
    {
        return round($this->attributes['price'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getCreditBeforeAttribute($value)
    {
        return round($value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setCreditBeforeAttribute($value)
    {
        return round($this->attributes['credit_before'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function getCreditAfterAttribute($value)
    {
        return round($value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setCreditAfterAttribute($value)
    {
        return round($this->attributes['credit_after'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }
}
