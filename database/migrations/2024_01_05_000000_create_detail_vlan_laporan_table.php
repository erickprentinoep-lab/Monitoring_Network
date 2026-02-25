<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_vlan_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_laporan')->constrained('laporan_harian')->onDelete('cascade');
            $table->foreignId('id_vlan')->constrained('vlans')->onDelete('cascade');
            $table->decimal('bandwidth_terpakai', 8, 2)->comment('Mbps aktual');
            $table->decimal('persentase', 5, 2)->nullable()->comment('% dari allocated');
            $table->decimal('packet_loss', 5, 2)->default(0)->comment('%');
            $table->decimal('delay', 8, 2)->default(0)->comment('ms');
            $table->decimal('jitter', 8, 2)->default(0)->comment('ms');
            $table->enum('status', ['UP', 'DOWN', 'Degraded'])->default('UP');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('detail_vlan_laporan');
    }
};
