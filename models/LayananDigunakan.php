<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class LayananDigunakan extends Model
{
    protected $table = 'layanan_digunakan';
    protected $fillable = ['nama'];
    public $timestamps = false; // nonaktifkan kalau tidak ada created_at/updated_at
}
