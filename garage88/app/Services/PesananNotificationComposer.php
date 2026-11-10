<?php

namespace App\Services;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Http;
use Throwable;

class PesananNotificationComposer
{
    public function compose(Pesanan $pesanan, string $previousStatus, string $newStatus, ?string $keterangan): array
    {
        $delayRisk = $this->hasDelayRisk($pesanan, $newStatus);
        $fallback = $this->fallbackMessage($pesanan, $newStatus, $keterangan, $delayRisk);
        $apiKey = config('services.openai.api_key');

        if (! $apiKey) {
            return ['message' => $fallback, 'delay_risk' => $delayRisk, 'ai_generated' => false];
        }

        try {
            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->timeout(6)
                ->post(config('services.openai.url'), [
                    'model' => config('services.openai.model'),
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.2,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tulis notifikasi pelanggan Garage88 dalam Bahasa Indonesia yang ramah, faktual, maksimal 35 kata. Keluarkan JSON dengan message (string) dan delay_risk (boolean). Jangan menjanjikan tanggal selesai. delay_risk true jika estimasi dekat atau terlewati sementara pekerjaan belum finishing/selesai.',
                        ],
                        [
                            'role' => 'user',
                            'content' => json_encode([
                                'pesanan' => $pesanan->id,
                                'motor' => trim($pesanan->motor->merk.' '.$pesanan->motor->tipe),
                                'status_sebelumnya' => $previousStatus,
                                'status_sekarang' => $newStatus,
                                'catatan_teknisi' => $keterangan,
                                'estimasi_selesai' => $pesanan->estimasi_selesai?->format('Y-m-d'),
                                'risiko_berdasarkan_tenggat' => $delayRisk,
                            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        ],
                    ],
                ])
                ->throw();

            $content = json_decode((string) $response->json('choices.0.message.content'), true);
            $message = is_array($content) ? trim((string) ($content['message'] ?? '')) : '';

            if ($message === '') {
                return ['message' => $fallback, 'delay_risk' => $delayRisk, 'ai_generated' => false];
            }

            return [
                'message' => $message,
                'delay_risk' => $delayRisk || (bool) ($content['delay_risk'] ?? false),
                'ai_generated' => true,
            ];
        } catch (Throwable) {
            return ['message' => $fallback, 'delay_risk' => $delayRisk, 'ai_generated' => false];
        }
    }

    private function hasDelayRisk(Pesanan $pesanan, string $status): bool
    {
        $deadline = $pesanan->estimasi_selesai;

        if (! $deadline || in_array($status, ['Finishing', 'Selesai', 'Dibatalkan'], true)) {
            return false;
        }

        return $deadline->lt(today()) || $deadline->isToday() || $deadline->isTomorrow();
    }

    private function fallbackMessage(Pesanan $pesanan, string $status, ?string $keterangan, bool $delayRisk): string
    {
        $motor = trim($pesanan->motor->merk.' '.$pesanan->motor->tipe);
        $message = "Progres repaint {$motor} (pesanan #{$pesanan->id}) kini {$status}.";

        $note = rtrim(trim((string) $keterangan), " \t\n\r\0\x0B.!?");
        if ($note !== '') {
            $message .= " Catatan teknisi: {$note}.";
        }

        if ($delayRisk) {
            $message .= ' Estimasi selesai perlu perhatian; silakan hubungi Garage88 untuk informasi terbaru.';
        }

        return $message;
    }
}