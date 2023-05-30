<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditCard extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'credit_cards';
    protected $fillable = [
        "number",
        "value",
        "qr",
        "credit_id",
        "supported_account_id",
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function supportedAccount()
    {
        return $this->belongsTo(SupportedAccount::class);
    }
}
