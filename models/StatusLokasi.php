<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class StatusLokasi extends Model
{
    protected $table = 'status_lokasi';
    protected $fillable = ['nama'];
    public $timestamps = false;

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'status_lokasi_id');
    }
}
