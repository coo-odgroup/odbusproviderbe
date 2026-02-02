<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPaymentArchive extends Model
{
    protected $guarded = [];
    protected $table = 'customer_payment'; // base name only

    protected static $year;

    public static function setYear($year)
    {
        static::$year = $year;
    }

    public function getTable()
    {
        if (static::$year) {
            return static::$year . '_' . $this->table;
        }

        return parent::getTable();
    }
}
