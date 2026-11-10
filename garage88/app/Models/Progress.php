<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progress extends Model
{
    protected $table = 'progress';

    public $timestamps = false;

    protected $fillable = ['pesanan_id', 'status', 'keterangan', 'tanggal_update'];

    protected function casts(): array
    {
        return ['tanggal_update' => 'datetime'];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    /** Method sesuai class diagram: updateStatus() */
    public static function updateStatus(Pesanan $pesanan, string $status, ?string $keterangan = null): self
    {
        $progress = static::create([
            'pesanan_id' => $pesanan->id,
            'status' => $status,
            'keterangan' => $keterangan,
            'tanggal_update' => now(),
        ]);

        $pesanan->update(['status' => $status]);

        return $progress;
    }
}
