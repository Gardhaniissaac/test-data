<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction1 extends Model
{
    public $timestamps = false;
    protected $table = 'transaction_1';
    protected $guarded = [];
}
