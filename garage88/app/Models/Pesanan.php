<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'pelanggan_id',
        'motor_id',
        'tanggal',
        'estimasi_selesai',
        'status',
        'bagian_direpaint',
        'warna_cat',
        'jenis_cat',
        'catatan',
        'total',
    ];

    public const AWAL = 'Menunggu Konfirmasi';

    /** Urutan tahapan pengerjaan repaint. */
    public const STATUS = [
        'Menunggu Konfirmasi',
        'Dikonfirmasi',
        'Pembongkaran',
        'Pengampelasan',
        'Pengecatan',
        'Finishing',
        'Selesai',
        'Dibatalkan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'estimasi_selesai' => 'date',
            'total' => 'decimal:0',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function motor(): BelongsTo
    {
        return $this->belongsTo(Motor::class);
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailPesanan::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class)->orderByDesc('tanggal_update')->orderByDesc('id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->orderBy('id');
    }

    public function verifiedPaymentTotal(): float
    {
        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->where('status', 'Terverifikasi')->sum('amount');
        }

        return (float) $this->payments()->where('status', 'Terverifikasi')->sum('amount');
    }

    public function paymentStatusLabel(): string
    {
        $paid = $this->verifiedPaymentTotal();

        if ($paid > 0 && $paid >= (float) $this->total) {
            return 'Lunas';
        }

        return $paid > 0 ? 'DP terverifikasi' : ($this->payment?->status ?? 'Belum dibayar');
    }

    public function isAktif(): bool
    {
        return ! in_array($this->status, ['Selesai', 'Dibatalkan'], true);
    }

    public function allowedStatusesFor(bool $isAdmin): array
    {
        if (! $this->isAktif()) {
            return [];
        }

        if ($isAdmin) {
            return match ($this->status) {
                self::AWAL => $this->verifiedPaymentTotal() > 0 ? ['Dikonfirmasi'] : [],
                'Finishing' => ['Selesai'],
                default => [],
            };
        }

        return match ($this->status) {
            'Dikonfirmasi' => ['Pembongkaran'],
            'Pembongkaran' => ['Pengampelasan'],
            'Pengampelasan' => ['Pengecatan'],
            'Pengecatan' => ['Finishing'],
            default => [],
        };
    }

    /**
     * Method sesuai class diagram: create().
     * Membuat pesanan + detail layanan + progress awal dalam satu transaksi.
     *
     * @param  array<int>  $layananIds
     */
    public static function buat(int $pelangganId, int $motorId, string $tanggal, array $layananIds, ?array $data = null): self
    {
        return DB::transaction(function () use ($pelangganId, $motorId, $tanggal, $layananIds, $data) {
            $layanan = Layanan::whereIn('id', $layananIds)->get();
            $extra = $data ?? [];

            $pesanan = static::create([
                'pelanggan_id' => $pelangganId,
                'motor_id' => $motorId,
                'tanggal' => $tanggal,
                'estimasi_selesai' => $extra['estimasi_selesai'] ?? null,
                'status' => self::AWAL,
                'bagian_direpaint' => $extra['bagian_direpaint'] ?? null,
                'warna_cat' => $extra['warna_cat'] ?? null,
                'jenis_cat' => $extra['jenis_cat'] ?? null,
                'catatan' => $extra['catatan'] ?? null,
                'total' => $layanan->sum('harga'),
            ]);

            foreach ($layanan as $l) {
                $pesanan->detail()->create(['layanan_id' => $l->id, 'harga' => $l->harga]);
            }

            Progress::create([
                'pesanan_id' => $pesanan->id,
                'status' => self::AWAL,
                'keterangan' => 'Pesanan diajukan, menunggu konfirmasi Garage88.',
                'tanggal_update' => now(),
            ]);

            return $pesanan;
        });
    }
}
