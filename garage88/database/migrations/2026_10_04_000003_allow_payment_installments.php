<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->index('pesanan_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['pesanan_id']);
            $table->string('type', 20)->default('pelunasan');
            $table->decimal('amount', 12, 0)->default(0);
        });

        DB::table('payments')->where('status', 'Lunas')->orderBy('id')->get()->each(function ($payment) {
            $amount = DB::table('pesanan')->where('id', $payment->pesanan_id)->value('total') ?? 0;
            DB::table('payments')->where('id', $payment->id)->update([
                'status' => 'Terverifikasi',
                'amount' => $amount,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['type', 'amount']);
            $table->unique('pesanan_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['pesanan_id']);
        });
    }
};