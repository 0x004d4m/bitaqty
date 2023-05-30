<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        "is_accepted",
        "product_id",
        "order_status_id",
        "userable_type",
        "userable_id",
    ];

    public function setUserableTypeAttribute()
    {
        $this->attributes["userable_type"] = request("userable_type");
    }

    public function setUserableIdAttribute()
    {
        $this->attributes["userable_id"] = request("userable");
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
}
