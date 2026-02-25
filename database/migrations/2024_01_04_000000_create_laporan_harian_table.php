<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan_harian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->decimal('total_bandwidth_tersedia', 8, 2)->comment('Mbps');
            $table->decimal('total_bandwidth_terpakai', 8, 2)->comment('Mbps');
            $table->decimal('persentase_bandwidth', 5, 2)->nullable()->comment('%');
            $table->enum('grade', ['A', 'B', 'C', 'D'])->nullable();
            $table->enum('status_jaringan', ['Normal', 'Degraded', 'Down'])->default('Normal');
            $table->string('isp_aktif', 100)->nullable()->comment('ISP yang sedang aktif saat laporan');
            $table->text('insiden')->nullable()->comment('Kejadian/masalah hari itu');
            $table->text('tindakan')->nullable()->comment('Tindakan yang diambil');
            $table->text('catatan')->nullable();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('laporan_harian');
    }
};
