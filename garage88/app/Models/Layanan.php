<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $fillable = ['nama_layanan', 'harga'];

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }
}
