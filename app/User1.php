<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User1 extends Model
{
    public $timestamps = false;
    protected $table = 'user_1';
    protected $fillable = ['user_id','op','ts','name','email','address'];
}
