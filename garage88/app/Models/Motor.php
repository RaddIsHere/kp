<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motor extends Model
{
    protected $table = 'motor';

    protected $fillable = ['pelanggan_id', 'plat_nomor', 'merk', 'tipe', 'tahun', 'warna'];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }

    public function getNamaLengkapAttribute(): string
    {
        $plat = $this->plat_nomor ? " / {$this->plat_nomor}" : '';

        return "{$this->merk} {$this->tipe} ({$this->tahun}) - {$this->warna}{$plat}";
    }
}
