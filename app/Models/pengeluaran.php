<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pengeluaran extends Model
{
    //

    use HasFactory;

    public $timestamps = false;

    protected $table = "pengeluaran_event";

    protected $primaryKey = "id";

    protected $fillable = [
        'id_event',
        'nama_pengeluaran',
        'kategori_pengeluaran',
        'jumlah',
        'status_pembayaran',
        'tanggal_transaksi'
    ];

    public function event( )
    {
        return $this->hasMany(Event::class);
    }

}
