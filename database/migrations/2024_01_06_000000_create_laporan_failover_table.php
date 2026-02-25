<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan_failover', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_laporan')->nullable()->constrained('laporan_harian')->nullOnDelete();
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->string('provider_dari', 100)->comment('ISP asal sebelum failover');
            $table->string('provider_ke', 100)->comment('ISP tujuan setelah failover');
            $table->enum('penyebab', ['Gangguan ISP', 'Maintenance', 'Manual', 'Lainnya'])->default('Gangguan ISP');
            $table->integer('durasi_menit')->nullable()->comment('Durasi failover dalam menit');
            $table->enum('status', ['Berjalan', 'Selesai'])->default('Selesai');
            $table->text('keterangan')->nullable();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('laporan_failover');
    }
};
