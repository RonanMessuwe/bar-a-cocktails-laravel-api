<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTable
 */
class Table extends Model
{
    use HasUlids;

    protected $primaryKey = 'table_id';

    public $timestamps = false;
}
