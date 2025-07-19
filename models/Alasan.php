<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Alasan extends Model
{
    protected $table = 'alasan';
    protected $fillable = ['nama'];
    public $timestamps = false;
}
