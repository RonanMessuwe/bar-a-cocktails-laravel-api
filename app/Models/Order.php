<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property String $order_id
 * @property OrderStatus $status
 * @mixin IdeHelperOrder
 */
class Order extends Model
{
    use HasUlids;
    use HasFactory;

    protected $primaryKey = 'order_id';

    static array $tables  = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12', 'T13', 'T14'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['status', 'table'];

    /* public function table(): HasOne
    {
        return $this->hasOne(Table::class);
    } */
}
