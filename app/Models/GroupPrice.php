<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;

class GroupPrice extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'group_prices';
    protected $fillable = [
        'group_id',
        'product_id',
        'price',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getPriceAttribute($value)
    {
        return round($value / Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }

    public function setPriceAttribute($value)
    {
        return round($this->attributes['price'] = $value * Currency::where('id', Session::get('currency'))->first()->to_jod, 3);
    }
}
