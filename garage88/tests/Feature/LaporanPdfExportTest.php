<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanPdfExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_laporan_as_pdf(): void
    {
        $this->seed();

        $admin = User::where('email', 'admin@garage88.test')->firstOrFail();

        $response = $this->actingAs($admin)
            ->get(route('admin.laporan.pdf', [
                'dari' => now()->startOfMonth()->toDateString(),
                'sampai' => now()->toDateString(),
            ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
