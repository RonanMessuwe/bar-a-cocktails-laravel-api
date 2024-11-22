<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperDrink
 */
class Drink extends Model
{
    use HasUlids;

    protected $primaryKey = 'drink_id';

    public $timestamps = false;

    // public function newUniqueId(): string
    // {
    //     return Str::ulid();
    // }

    // public function uniqueIds(): array
    // {
    //     return ['drink_id'];
    // }

    // public static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($model) {
    //         //$model->{$model->getKeyName()} = Str::ulid();
    //         $model->drink_id = Str::ulid();
    //     });
    // }
}
