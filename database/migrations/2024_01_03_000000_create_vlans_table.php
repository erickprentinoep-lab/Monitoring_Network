<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vlans', function (Blueprint $table) {
            $table->id();
            $table->integer('vlan_id')->comment('VLAN number: 10, 20, 30...');
            $table->string('nama', 100)->comment('cth: Staff, Tamu, Server');
            $table->string('departemen', 100)->nullable();
            $table->decimal('bandwidth_allocated', 8, 2)->comment('Mbps jatah VLAN ini');
            $table->string('subnet', 50)->nullable()->comment('cth: 192.168.10.0/24');
            $table->string('gateway', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('vlans');
    }
};
