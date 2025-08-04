<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    // Nama tabel di database
    protected $table = 'registrasi_tbl_pendaftaran';

    // Auto-increment pakai default sequence PostgreSQL
    protected $primaryKey = 'id';
    public $incrementing = true;

    // Format primary key integer
    protected $keyType = 'int';

    // Nonaktifkan timestamps otomatis
    public $timestamps = false;

    // Field yang boleh diisi (mass assignment)
    protected $fillable = [
    'mgm_id',
    'jenis_daf_id',
    'nama_lengkap',
    'whatsapp',
    'alamat',
    'rt',
    'rw',
    'provinsi_id',
    'kabupaten_id',
    'kecamatan_id',
    'kelurahan_id',
    'patokan',
    'nik',
    'foto_ktp',
    'foto_rmh',
    'foto_kk',
    'status_lokasi_id',
    'produk_id',
    'tahu_layanan_id',
    'alasan_id',
    'layanan_digunakan_id',
    'unit_id',
    'tanggal',
    'pegawai_id',
    'stts_create',
    'stts_ver',
    'longlat',
    'userid_app',     // ✅ ditambahkan
    'order_id'        // ✅ ditambahkan
];


    // Konversi kolom JSON otomatis ke array PHP
    protected $casts = [
        'whatsapp' => 'array',
    ];

    // Relasi ke tabel lain
    public function alasan()
    {
        return $this->belongsTo(Alasan::class, 'alasan_id');
    }

    public function layananDigunakan()
    {
        return $this->belongsTo(LayananDigunakan::class, 'layanan_digunakan_id');
    }

    public function statusLokasi()
    {
        return $this->belongsTo(StatusLokasi::class, 'status_lokasi_id');
    }

    public function tahuLayanan()
    {
        return $this->belongsTo(TahuLayanan::class, 'tahu_layanan_id');
    }
}
