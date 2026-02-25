<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('konfigurasi_jaringan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan', 150);
            $table->string('isp_utama', 100)->nullable();
            $table->string('isp_backup', 100)->nullable();
            $table->decimal('bandwidth_utama', 8, 2)->nullable()->comment('Mbps');
            $table->decimal('bandwidth_backup', 8, 2)->nullable()->comment('Mbps');
            $table->string('perangkat_utama', 150)->nullable()->comment('cth: Mikrotik CCR1036');
            $table->text('konfigurasi_qos')->nullable();
            $table->text('konfigurasi_failover')->nullable();
            $table->text('konfigurasi_vlan')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_jaringan');
    }
};
