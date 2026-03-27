<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Representation of an order record in the MyBidhaa marketplace database (ec_orders table).
 *
 * Uses the dedicated "mybidhaa" database connection configured in database.php.
 */
class MyBidhaaOrder extends Model
{
    protected $connection = 'mybidhaa';

    protected $table = 'ec_orders';

    protected $guarded = [];
}

