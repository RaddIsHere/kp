<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Motor;
use App\Models\Payment;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\User;
use Database\Seeders\PriceListSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Pengujian Black Box (Bab IV laporan):
 * Login, pengajuan layanan, pembayaran, chat, update status, dan pembatasan akses per role.
 */
class Garage88Test extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => "$role@test.local",
            'password' => 'password',
            'role' => $role,
        ]);
    }

    private function pelanggan(): Pelanggan
    {
        $u = $this->user('pelanggan');

        return Pelanggan::create(['user_id' => $u->id, 'alamat' => 'Jl. Test', 'no_hp' => '0812']);
    }

    public function test_halaman_beranda_dapat_dibuka(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_daftar_harga_motor_masuk_ke_katalog_layanan(): void
    {
        $this->seed(PriceListSeeder::class);

        $this->assertDatabaseCount('layanan', 22);
        $this->assertDatabaseHas('layanan', ['nama_layanan' => 'Beat / Mio - Body Halus', 'harga' => 700000]);
        $this->assertDatabaseHas('layanan', ['nama_layanan' => 'X-Max - Full Restorasi', 'harga' => 2100000]);
        $this->assertDatabaseMissing('layanan', ['nama_layanan' => 'RX-King - Body Kasar']);
    }

    public function test_login_data_valid_berhasil(): void
    {
        $this->user('admin');

        $this->post('/login', ['email' => 'admin@test.local', 'password' => 'password'])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
    }

    public function test_login_data_salah_gagal(): void
    {
        $this->user('admin');

        $this->post('/login', ['email' => 'admin@test.local', 'password' => 'salah'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registrasi_pelanggan(): void
    {
        $this->post('/register', [
            'name' => 'Andi', 'email' => 'andi@test.local',
            'password' => 'rahasia', 'password_confirmation' => 'rahasia',
            'no_hp' => '0811', 'alamat' => 'Cianjur',
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('users', ['email' => 'andi@test.local', 'role' => 'pelanggan']);
        $this->assertDatabaseCount('pelanggan', 1);
    }

    public function test_semua_dashboard_dapat_dibuka(): void
    {
        foreach (['admin', 'teknisi'] as $role) {
            $this->actingAs($this->user($role))->get('/dashboard')->assertOk();
        }
        $p = $this->pelanggan();
        $this->actingAs($p->user)->get('/dashboard')->assertOk();
    }

    public function test_pelanggan_mengajukan_layanan_dengan_motor_baru(): void
    {
        $p = $this->pelanggan();
        $l1 = Layanan::create(['nama_layanan' => 'Repaint Velg', 'harga' => 350000]);
        $l2 = Layanan::create(['nama_layanan' => 'Paint Correction', 'harga' => 750000]);

        $this->actingAs($p->user)->post(route('pelanggan.simpan'), [
            'motor_id' => 'baru', 'merk' => 'Honda', 'tipe' => 'Vario', 'tahun' => '2020', 'warna' => 'Merah',
            'tanggal' => now()->addWeeks(2)->toDateString(),
            'layanan' => [$l1->id, $l2->id],
        ])->assertRedirect();

        $pesanan = Pesanan::first();
        $this->assertSame('Menunggu Konfirmasi', $pesanan->status);
        $this->assertEquals(1100000, $pesanan->total);
        $this->assertCount(2, $pesanan->detail);
        $this->assertCount(1, $pesanan->progress);
        $this->assertDatabaseHas('motor', ['pelanggan_id' => $p->id, 'tipe' => 'Vario']);
    }

    public function test_pengajuan_tanpa_layanan_ditolak(): void
    {
        $p = $this->pelanggan();
        $m = Motor::create(['pelanggan_id' => $p->id, 'merk' => 'Yamaha', 'tipe' => 'NMAX', 'tahun' => '2021', 'warna' => 'Hitam']);

        $this->actingAs($p->user)->post(route('pelanggan.simpan'), [
            'motor_id' => $m->id, 'tanggal' => now()->addWeeks(2)->toDateString(),
        ])->assertSessionHasErrors('layanan');
        $this->assertDatabaseCount('pesanan', 0);
    }

    public function test_pengajuan_layanan_menyimpan_detail_repaint_dan_plat_nomor(): void
    {
        $p = $this->pelanggan();
        $l = Layanan::create(['nama_layanan' => 'Repaint Full Body', 'harga' => 1500000]);

        $this->actingAs($p->user)->post(route('pelanggan.simpan'), [
            'motor_id' => 'baru',
            'plat_nomor' => 'B 1234 ABC',
            'merk' => 'Honda',
            'tipe' => 'CB150R',
            'tahun' => '2020',
            'warna' => 'Merah',
            'tanggal' => now()->addWeeks(2)->toDateString(),
            'estimasi_selesai' => now()->addDays(21)->toDateString(),
            'bagian_direpaint' => 'Body depan & fairing',
            'warna_cat' => 'Hitam doff',
            'jenis_cat' => 'Acrylic',
            'catatan' => 'Cat dasar perlu proses sanding',
            'layanan' => [$l->id],
        ])->assertRedirect();

        $this->assertDatabaseHas('motor', ['pelanggan_id' => $p->id, 'plat_nomor' => 'B 1234 ABC']);
        $this->assertDatabaseHas('pesanan', [
            'pelanggan_id' => $p->id,
            'bagian_direpaint' => 'Body depan & fairing',
            'warna_cat' => 'Hitam doff',
            'jenis_cat' => 'Acrylic',
        ]);
    }

    public function test_admin_tidak_memiliki_route_tambah_manual(): void
    {
        foreach (['pelanggan', 'motor', 'pesanan'] as $resource) {
            $this->assertFalse(Route::has("admin.{$resource}.create"));
            $this->assertFalse(Route::has("admin.{$resource}.store"));
        }
        $this->assertTrue(Route::has('admin.layanan.create'));
        $this->assertTrue(Route::has('admin.layanan.store'));
    }

    public function test_admin_dapat_menambahkan_layanan(): void
    {
        $this->actingAs($this->user('admin'))->post(route('admin.layanan.store'), [
            'nama_layanan' => 'Repaint Full Body',
            'harga' => 1500000,
        ])->assertRedirect(route('admin.layanan.index'));

        $this->assertDatabaseHas('layanan', ['nama_layanan' => 'Repaint Full Body', 'harga' => 1500000]);
    }

    public function test_peran_admin_dan_teknisi_dibatasi_pada_tahap_pesanan(): void
    {
        $pelanggan = $this->pelanggan();
        $motor = Motor::create(['pelanggan_id' => $pelanggan->id, 'merk' => 'Honda', 'tipe' => 'CB150R', 'tahun' => '2019', 'warna' => 'Hitam']);
        $layanan = Layanan::create(['nama_layanan' => 'Repaint Velg', 'harga' => 350000]);
        $pesanan = Pesanan::buat($pelanggan->id, $motor->id, now()->toDateString(), [$layanan->id]);
        $admin = $this->user('admin');

        Payment::create([
            'pesanan_id' => $pesanan->id,
            'type' => 'dp',
            'amount' => 100000,
            'method' => 'tunai',
            'status' => 'Terverifikasi',
            'verified_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post(route('teknisi.progress.store', $pesanan), ['status' => 'Pengecatan'])
            ->assertSessionHasErrors('status');
        $this->post(route('teknisi.progress.store', $pesanan), ['status' => 'Selesai'])
            ->assertSessionHasErrors('status');
        $this->post(route('teknisi.progress.store', $pesanan), ['status' => 'Dikonfirmasi'])
            ->assertSessionHasNoErrors();

        $this->actingAs($this->user('teknisi'))
            ->post(route('teknisi.progress.store', $pesanan), ['status' => 'Selesai'])
            ->assertSessionHasErrors('status');

        $pesanan->update(['status' => 'Finishing']);
        $this->actingAs($admin)
            ->post(route('teknisi.progress.store', $pesanan), ['status' => 'Selesai'])
            ->assertSessionHasNoErrors();
        $this->assertSame('Selesai', $pesanan->fresh()->status);
    }

    public function test_teknisi_update_status_berhasil(): void
    {
        $p = $this->pelanggan();
        $m = Motor::create(['pelanggan_id' => $p->id, 'merk' => 'Honda', 'tipe' => 'CB150R', 'tahun' => '2019', 'warna' => 'Hitam']);
        $l = Layanan::create(['nama_layanan' => 'Repaint Velg', 'harga' => 350000]);
        $pesanan = Pesanan::buat($p->id, $m->id, now()->toDateString(), [$l->id]);
        $pesanan->update(['status' => 'Pengampelasan']);

        $this->actingAs($this->user('teknisi'))
            ->post(route('teknisi.progress.store', $pesanan), ['status' => 'Pengecatan', 'keterangan' => 'Proses cat dasar'])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('progress_updated', true);

        $this->assertSame('Pengecatan', $pesanan->fresh()->status);
        $this->assertCount(2, $pesanan->fresh()->progress);
        $this->assertDatabaseCount('notifications', 1);
        $this->assertStringNotContainsString('..', $p->user->notifications()->firstOrFail()->data['message']);
        $this->get(route('teknisi.pekerjaan.show', $pesanan))
            ->assertSee('timeline-current', false)
            ->assertSee('timeline-highlight', false);

        $this->actingAs($p->user)
            ->get(route('pelanggan.notifikasi'))
            ->assertOk()
            ->assertSee('Pembaruan pesanan #'.$pesanan->id)
            ->assertSee('Pengecatan');

        $notification = $p->user->notifications()->firstOrFail();
        $this->post(route('pelanggan.notifikasi.baca', $notification->id))
            ->assertRedirect(route('pelanggan.detail', $pesanan));
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_status_tidak_valid_ditolak(): void
    {
        $p = $this->pelanggan();
        $m = Motor::create(['pelanggan_id' => $p->id, 'merk' => 'Honda', 'tipe' => 'CB150R', 'tahun' => '2019', 'warna' => 'Hitam']);
        $l = Layanan::create(['nama_layanan' => 'Repaint Velg', 'harga' => 350000]);
        $pesanan = Pesanan::buat($p->id, $m->id, now()->toDateString(), [$l->id]);

        $this->actingAs($this->user('teknisi'))
            ->post(route('teknisi.progress.store', $pesanan), ['status' => 'Ngawur'])
            ->assertSessionHasErrors('status');
    }

    public function test_teknisi_tidak_dapat_membatalkan_pesanan(): void
    {
        $pelanggan = $this->pelanggan();
        $motor = Motor::create(['pelanggan_id' => $pelanggan->id, 'merk' => 'Honda', 'tipe' => 'Beat', 'tahun' => '2019', 'warna' => 'Hitam']);
        $layanan = Layanan::create(['nama_layanan' => 'Body Halus', 'harga' => 700000]);
        $pesanan = Pesanan::buat($pelanggan->id, $motor->id, now()->toDateString(), [$layanan->id]);
        $teknisi = $this->user('teknisi');

        $this->actingAs($teknisi)->get(route('teknisi.pekerjaan.show', $pesanan))
            ->assertOk()->assertDontSee('Batalkan Pesanan');
        $this->post(route('teknisi.progress.store', $pesanan), ['status' => 'Dibatalkan'])
            ->assertSessionHasErrors('status');

        $this->assertSame('Menunggu Konfirmasi', $pesanan->fresh()->status);
        $this->assertFalse(Route::has('teknisi.pekerjaan.cancel'));
    }

    public function test_ai_menyusun_notifikasi_dan_mendeteksi_risiko_estimasi(): void
    {
        config(['services.openai.api_key' => 'test-api-key']);
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'message' => 'Motor Anda sudah masuk tahap pengecatan. Estimasi selesai perlu kami pantau.',
                            'delay_risk' => true,
                        ]),
                    ],
                ]],
            ]),
        ]);

        $p = $this->pelanggan();
        $m = Motor::create(['pelanggan_id' => $p->id, 'merk' => 'Honda', 'tipe' => 'CB150R', 'tahun' => '2019', 'warna' => 'Hitam']);
        $l = Layanan::create(['nama_layanan' => 'Repaint Velg', 'harga' => 350000]);
        $pesanan = Pesanan::buat($p->id, $m->id, now()->toDateString(), [$l->id], [
            'estimasi_selesai' => today()->addDay()->toDateString(),
        ]);
        $pesanan->update(['status' => 'Pengampelasan']);

        $this->actingAs($this->user('teknisi'))
            ->post(route('teknisi.progress.store', $pesanan), ['status' => 'Pengecatan'])
            ->assertSessionHasNoErrors();

        $notification = $p->user->notifications()->firstOrFail();
        $this->assertSame('Motor Anda sudah masuk tahap pengecatan. Estimasi selesai perlu kami pantau.', $notification->data['message']);
        $this->assertTrue($notification->data['ai_generated']);
        $this->assertTrue($notification->data['delay_risk']);
        $this->assertStringNotContainsString('..', $notification->data['message']);
        Http::assertSent(fn ($request) => $request->url() === 'https://api.openai.com/v1/chat/completions');
    }

    public function test_pelanggan_bayar_dan_admin_memverifikasi_pesanan(): void
    {
        Storage::fake('local');
        $pelanggan = $this->pelanggan();
        $motor = Motor::create(['pelanggan_id' => $pelanggan->id, 'merk' => 'Honda', 'tipe' => 'CB150R', 'tahun' => '2019', 'warna' => 'Hitam']);
        $layanan = Layanan::create(['nama_layanan' => 'Repaint Velg', 'harga' => 350000]);
        $pesanan = Pesanan::buat($pelanggan->id, $motor->id, now()->toDateString(), [$layanan->id]);

        $this->actingAs($pelanggan->user)->post(route('pelanggan.pembayaran', $pesanan), [
            'type' => 'dp',
            'amount' => 100000,
            'method' => 'transfer',
            'proof' => UploadedFile::fake()->image('bukti.jpg'),
        ])->assertSessionHasNoErrors();

        $dp = $pesanan->fresh()->payments()->firstOrFail();
        $this->assertSame('Menunggu verifikasi', $dp->status);
        Storage::disk('local')->assertExists($dp->proof_path);
        $this->actingAs($pelanggan->user)->get(route('pelanggan.bukti-pembayaran', [$pesanan, $dp]))->assertOk();

        $admin = $this->user('admin');
        $this->actingAs($admin)->post(route('teknisi.progress.store', $pesanan), [
            'status' => 'Dikonfirmasi',
        ])->assertSessionHasErrors('status');
        $this->post(route('admin.pesanan.pembayaran', [$pesanan, $dp]), [
            'status' => 'Terverifikasi',
        ])->assertSessionHasNoErrors();
        $this->post(route('teknisi.progress.store', $pesanan), ['status' => 'Dikonfirmasi'])->assertSessionHasNoErrors();
        $this->assertSame('Dikonfirmasi', $pesanan->fresh()->status);

        $this->actingAs($pelanggan->user)->post(route('pelanggan.pembayaran', $pesanan), [
            'type' => 'pelunasan',
            'amount' => 250000,
            'method' => 'dana',
        ])->assertSessionHasErrors('proof');

        $pelunasanResponse = $this->post(route('pelanggan.pembayaran', $pesanan), [
            'type' => 'pelunasan',
            'amount' => 250000,
            'method' => 'dana',
            'proof' => UploadedFile::fake()->image('pelunasan.jpg'),
        ]);
        $pelunasanResponse->assertRedirect()->assertSessionHasNoErrors();
        $this->assertDatabaseCount('payments', 2);
        $pelunasan = $pesanan->fresh()->payments()->reorder()->latest('id')->firstOrFail();
        $this->assertSame('dana', $pelunasan->method);
        $this->actingAs($admin)->post(route('admin.pesanan.pembayaran', [$pesanan, $pelunasan]), [
            'status' => 'Terverifikasi',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('payments', ['id' => $pelunasan->id, 'status' => 'Terverifikasi', 'amount' => 250000]);
        $this->assertSame(350000.0, $pesanan->fresh()->verifiedPaymentTotal());
        $this->actingAs($pelanggan->user)->get(route('pelanggan.detail', $pesanan))->assertOk()->assertSee('Lunas')->assertSee('DANA');
    }

    public function test_pelanggan_chat_dengan_admin(): void
    {
        $pelanggan = $this->pelanggan();

        $this->actingAs($pelanggan->user)->post(route('pelanggan.chat.store'), ['message' => 'Halo admin'])
            ->assertRedirect(route('pelanggan.chat'));
        $this->assertDatabaseHas('chat_messages', ['pelanggan_id' => $pelanggan->id, 'message' => 'Halo admin']);

        $admin = $this->user('admin');
        $this->actingAs($admin)->get(route('admin.chat.show', $pelanggan))->assertOk()->assertSee('Halo admin');
        $this->actingAs($admin)->post(route('admin.chat.store', $pelanggan), ['message' => 'Siap dibantu'])
            ->assertRedirect(route('admin.chat.show', $pelanggan));
        $this->actingAs($pelanggan->user)->get(route('pelanggan.chat'))->assertOk()->assertSee('Siap dibantu');
    }

    public function test_pembatasan_akses_per_role(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');

        $pelanggan = $this->pelanggan();
        $this->actingAs($pelanggan->user)->get(route('admin.pelanggan.index'))->assertForbidden();
        $this->actingAs($pelanggan->user)->get(route('teknisi.pekerjaan'))->assertForbidden();
        $this->actingAs($this->user('teknisi'))->get(route('admin.laporan'))->assertForbidden();
    }

    public function test_pelanggan_tidak_bisa_melihat_pesanan_orang_lain(): void
    {
        $a = $this->pelanggan();
        $ub = User::create(['name' => 'B', 'email' => 'b@test.local', 'password' => 'password', 'role' => 'pelanggan']);
        Pelanggan::create(['user_id' => $ub->id]);

        $m = Motor::create(['pelanggan_id' => $a->id, 'merk' => 'Honda', 'tipe' => 'Beat', 'tahun' => '2018', 'warna' => 'Putih']);
        $l = Layanan::create(['nama_layanan' => 'Repaint Velg', 'harga' => 350000]);
        $pesanan = Pesanan::buat($a->id, $m->id, now()->toDateString(), [$l->id]);

        $this->actingAs($ub)->get(route('pelanggan.detail', $pesanan))->assertForbidden();
        $this->actingAs($a->user)->get(route('pelanggan.detail', $pesanan))->assertOk();
    }

    public function test_laporan_menghitung_pembayaran_yang_sudah_lunas(): void
    {
        $p = $this->pelanggan();
        $m = Motor::create(['pelanggan_id' => $p->id, 'merk' => 'Honda', 'tipe' => 'Beat', 'tahun' => '2018', 'warna' => 'Putih']);
        $l = Layanan::create(['nama_layanan' => 'Repaint Velg', 'harga' => 350000]);
        $selesai = Pesanan::buat($p->id, $m->id, now()->toDateString(), [$l->id]);
        $selesai->update(['status' => 'Selesai']);
        Payment::create(['pesanan_id' => $selesai->id, 'type' => 'pelunasan', 'amount' => 350000, 'method' => 'tunai', 'status' => 'Terverifikasi', 'verified_at' => now()]);
        Pesanan::buat($p->id, $m->id, now()->toDateString(), [$l->id]); // belum selesai
        $this->assertSame(350000.0, $selesai->fresh()->verifiedPaymentTotal());

        $response = $this->actingAs($this->user('admin'))->get(route('admin.laporan', [
            'dari' => '2026-01-01',
            'sampai' => '2027-01-01',
        ]))->assertOk();
        $this->assertCount(2, $response->viewData('items'));
        $this->assertSame(350000.0, $response->viewData('pendapatan'));
    }

    public function test_layanan_yang_dipakai_tidak_bisa_dihapus(): void
    {
        $p = $this->pelanggan();
        $m = Motor::create(['pelanggan_id' => $p->id, 'merk' => 'Honda', 'tipe' => 'Beat', 'tahun' => '2018', 'warna' => 'Putih']);
        $l = Layanan::create(['nama_layanan' => 'Repaint Velg', 'harga' => 350000]);
        Pesanan::buat($p->id, $m->id, now()->toDateString(), [$l->id]);

        $this->actingAs($this->user('admin'))->delete(route('admin.layanan.destroy', $l))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('layanan', ['id' => $l->id]);
    }
}
