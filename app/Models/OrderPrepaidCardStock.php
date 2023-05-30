<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderPrepaidCardStock extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order_prepaid_card_stocks';
    protected $fillable = [
        "is_printed",
        "order_id",
        "prepaid_card_stock_id",
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function prepaidCardStock()
    {
        return $this->belongsTo(PrepaidCardStock::class);
    }
}
