<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['pesanan_id', 'method', 'type', 'amount', 'status', 'proof_path', 'admin_note', 'verified_at'];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime', 'amount' => 'decimal:0'];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }
}