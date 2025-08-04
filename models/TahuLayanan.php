<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class TahuLayanan extends Model
{
    protected $table = 'tahu_layanan';
    protected $fillable = ['nama'];
    public $timestamps = false;

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'tahu_layanan_id');
    }
}
