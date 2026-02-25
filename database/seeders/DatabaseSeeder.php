<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\KonfigurasiJaringan;
use App\Models\Vlan;
use App\Models\LaporanHarian;
use App\Models\DetailVlanLaporan;
use App\Models\LaporanFailover;
use App\Helpers\GradeHelper;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $admin = User::create(['nama' => 'Administrator IT', 'username' => 'admin', 'password' => bcrypt('admin123'), 'role' => 'admin']);
        $manager = User::create(['nama' => 'Manager Jaringan', 'username' => 'manager', 'password' => bcrypt('manager123'), 'role' => 'manager']);

        // Konfigurasi Jaringan
        KonfigurasiJaringan::create([
            'nama_perusahaan' => 'PT. Maju Bersama Indonesia',
            'isp_utama' => 'Telkom IndiHome',
            'isp_backup' => 'Biznet Fiber',
            'bandwidth_utama' => 200,
            'bandwidth_backup' => 100,
            'perangkat_utama' => 'Mikrotik CCR1036-12G-4S',
            'konfigurasi_qos' => "- Queue Tree dengan PCQ untuk fairness per user\n- Burst limit 20% dari alokasi per VLAN\n- Priority: VLAN Server (8) > VLAN Staff (6) > VLAN Tamu (4)\n- Bandwidth shaping per departemen",
            'konfigurasi_failover' => "- Dual WAN Load Balance + Failover\n- Rekursif routing check via 8.8.8.8 dan 8.8.4.4\n- Failover otomatis jika link down > 3 ping gagal\n- Waktu pemulihan manual: maks 5 menit",
            'konfigurasi_vlan' => "- VLAN 10: Manajemen (192.168.10.0/24)\n- VLAN 20: Staff IT (192.168.20.0/24)\n- VLAN 30: Staff Umum (192.168.30.0/24)\n- VLAN 40: Server Farm (192.168.40.0/24)\n- VLAN 50: Tamu/WiFi (192.168.50.0/24)",
            'catatan' => 'Backup konfigurasi rutin setiap Jumat pukul 17.00 WIB.',
            'updated_by' => $admin->id,
        ]);

        // VLAN Master
        $vlans = [
            ['vlan_id' => 10, 'nama' => 'Manajemen', 'departemen' => 'Management', 'bandwidth_allocated' => 20, 'subnet' => '192.168.10.0/24', 'gateway' => '192.168.10.1', 'deskripsi' => 'VLAN untuk tim manajemen'],
            ['vlan_id' => 20, 'nama' => 'IT', 'departemen' => 'IT Support', 'bandwidth_allocated' => 50, 'subnet' => '192.168.20.0/24', 'gateway' => '192.168.20.1', 'deskripsi' => 'VLAN untuk tim IT dan server monitoring'],
            ['vlan_id' => 30, 'nama' => 'Staff Umum', 'departemen' => 'Umum', 'bandwidth_allocated' => 80, 'subnet' => '192.168.30.0/24', 'gateway' => '192.168.30.1', 'deskripsi' => 'VLAN untuk seluruh staff kantor'],
            ['vlan_id' => 40, 'nama' => 'Server Farm', 'departemen' => 'IT Support', 'bandwidth_allocated' => 60, 'subnet' => '192.168.40.0/24', 'gateway' => '192.168.40.1', 'deskripsi' => 'VLAN untuk server internal'],
            ['vlan_id' => 50, 'nama' => 'Tamu/WiFi', 'departemen' => 'Umum', 'bandwidth_allocated' => 30, 'subnet' => '192.168.50.0/24', 'gateway' => '192.168.50.1', 'deskripsi' => 'VLAN untuk tamu dan WiFi publik'],
        ];
        $vlanModels = [];
        foreach ($vlans as $v) {
            $vlanModels[] = Vlan::create(array_merge($v, ['aktif' => true]));
        }

        // Sample Laporan Harian — 14 hari ke belakang
        $sampleData = [
            ['bw_terpakai' => 170, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 185, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 160, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 140, 'status' => 'Degraded', 'isp' => 'Biznet Fiber', 'insiden' => 'ISP utama mengalami gangguan', 'tindakan' => 'Failover ke Biznet Fiber'],
            ['bw_terpakai' => 190, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 175, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 180, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 120, 'status' => 'Down', 'isp' => 'Biznet Fiber', 'insiden' => 'Mati listrik 2 jam, jaringan down', 'tindakan' => 'Restart perangkat setelah PLN pulih'],
            ['bw_terpakai' => 195, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 168, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 182, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 172, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 188, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
            ['bw_terpakai' => 176, 'status' => 'Normal', 'isp' => 'Telkom IndiHome'],
        ];

        $tersedia = 200;
        foreach ($sampleData as $idx => $d) {
            $tanggal = Carbon::today()->subDays(13 - $idx);
            $persen = GradeHelper::hitungPersentase($tersedia, $d['bw_terpakai']);
            $grade = GradeHelper::hitungGrade($tersedia, $d['bw_terpakai']);

            $laporan = LaporanHarian::create([
                'tanggal' => $tanggal->toDateString(),
                'total_bandwidth_tersedia' => $tersedia,
                'total_bandwidth_terpakai' => $d['bw_terpakai'],
                'persentase_bandwidth' => $persen,
                'grade' => $grade,
                'status_jaringan' => $d['status'],
                'isp_aktif' => $d['isp'],
                'insiden' => $d['insiden'] ?? null,
                'tindakan' => $d['tindakan'] ?? null,
                'catatan' => null,
                'id_user' => $admin->id,
            ]);

            // Detail per VLAN
            $bwPerVlan = [16, 40, 64, 48, 24]; // proporsi mirip allocated
            foreach ($vlanModels as $i => $vlan) {
                $bwVlan = round($bwPerVlan[$i] * ($d['bw_terpakai'] / 200) + rand(-2, 2), 2);
                $pVlan = GradeHelper::hitungPersentase($vlan->bandwidth_allocated, max(0, $bwVlan));
                DetailVlanLaporan::create([
                    'id_laporan' => $laporan->id,
                    'id_vlan' => $vlan->id,
                    'bandwidth_terpakai' => max(0, $bwVlan),
                    'persentase' => $pVlan,
                    'packet_loss' => $d['status'] === 'Down' ? rand(20, 60) : round(rand(0, 3) * 0.1, 2),
                    'delay' => $d['status'] === 'Down' ? rand(200, 500) : rand(5, 50),
                    'jitter' => $d['status'] === 'Down' ? rand(30, 100) : rand(1, 15),
                    'status' => $d['status'] === 'Down' ? 'DOWN' : ($d['status'] === 'Degraded' && $i > 2 ? 'Degraded' : 'UP'),
                    'catatan' => null,
                ]);
            }
        }

        // Sample Failover
        LaporanFailover::create([
            'id_laporan' => LaporanHarian::where('status_jaringan', 'Degraded')->first()?->id,
            'tanggal' => Carbon::today()->subDays(10)->toDateString(),
            'waktu_mulai' => '09:14:00',
            'waktu_selesai' => '09:47:00',
            'provider_dari' => 'Telkom IndiHome',
            'provider_ke' => 'Biznet Fiber',
            'penyebab' => 'Gangguan ISP',
            'durasi_menit' => 33,
            'status' => 'Selesai',
            'keterangan' => 'ISP utama Telkom down, failover otomatis ke Biznet berjalan normal.',
            'id_user' => $admin->id,
        ]);
        LaporanFailover::create([
            'id_laporan' => LaporanHarian::where('status_jaringan', 'Down')->first()?->id,
            'tanggal' => Carbon::today()->subDays(6)->toDateString(),
            'waktu_mulai' => '13:22:00',
            'waktu_selesai' => '15:05:00',
            'provider_dari' => 'Telkom IndiHome',
            'provider_ke' => 'Biznet Fiber',
            'penyebab' => 'Lainnya',
            'durasi_menit' => 103,
            'status' => 'Selesai',
            'keterangan' => 'Mati listrik total menyebabkan semua perangkat mati. Setelah PLN pulih, sistem online kembali via Biznet.',
            'id_user' => $admin->id,
        ]);
    }
}
