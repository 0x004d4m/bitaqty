<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use CrudTrait;
    use HasFactory;
    use SoftDeletes;
    use HasTranslations;

    protected $table = 'currencies';
    protected $translatable = [
        'name',
    ];
    protected $fillable = [
        'name',
        'symbol',
        'to_usd',
        'country_id',
    ];
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
