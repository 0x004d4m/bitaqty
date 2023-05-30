<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrepaidCardStock extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'prepaid_card_stocks';
    protected $fillable = [
        'serial1',
        'serial2',
        'number1',
        'number2',
        'cvc',
        'expiration_date',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
