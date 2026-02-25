<?php

namespace App\Helpers;

class QosHelper
{
    /**
     * Hitung kategori QoS berdasarkan standar TIPHON
     * Throughput: Kbps, Delay: ms, Jitter: ms, Packet Loss: %
     */
    public static function hitungKategori(float $throughput, float $delay, float $jitter, float $packetLoss): string
    {
        $skor = self::skorThroughput($throughput)
            + self::skorDelay($delay)
            + self::skorJitter($jitter)
            + self::skorPacketLoss($packetLoss);

        $rataRata = $skor / 4;

        if ($rataRata >= 3.5)
            return 'Sangat Baik';
        if ($rataRata >= 2.5)
            return 'Baik';
        if ($rataRata >= 1.5)
            return 'Sedang';
        return 'Buruk';
    }

    public static function skorThroughput(float $throughput): int
    {
        if ($throughput >= 1000)
            return 4;   // >= 1 Mbps
        if ($throughput >= 512)
            return 3;   // 512 Kbps - 1 Mbps
        if ($throughput >= 256)
            return 2;   // 256 - 512 Kbps
        return 1;                             // < 256 Kbps
    }

    public static function skorDelay(float $delay): int
    {
        if ($delay < 150)
            return 4;
        if ($delay < 300)
            return 3;
        if ($delay < 450)
            return 2;
        return 1;
    }

    public static function skorJitter(float $jitter): int
    {
        if ($jitter < 20)
            return 4;
        if ($jitter < 50)
            return 3;
        if ($jitter < 75)
            return 2;
        return 1;
    }

    public static function skorPacketLoss(float $packetLoss): int
    {
        if ($packetLoss <= 1)
            return 4;
        if ($packetLoss <= 5)
            return 3;
        if ($packetLoss <= 25)
            return 2;
        return 1;
    }

    public static function badgeKategori(string $kategori): string
    {
        return match ($kategori) {
            'Sangat Baik' => 'success',
            'Baik' => 'info',
            'Sedang' => 'warning',
            'Buruk' => 'danger',
            default => 'secondary',
        };
    }

    public static function generateKesimpulan(array $data): string
    {
        $throughput = $data['throughput'];
        $delay = $data['delay'];
        $jitter = $data['jitter'];
        $packetLoss = $data['packet_loss'];
        $kategori = $data['kategori'];

        $masalah = [];
        if ($throughput < 512)
            $masalah[] = "throughput rendah ({$throughput} Kbps)";
        if ($delay >= 150)
            $masalah[] = "delay tinggi ({$delay} ms)";
        if ($jitter >= 20)
            $masalah[] = "jitter tinggi ({$jitter} ms)";
        if ($packetLoss > 1)
            $masalah[] = "packet loss {$packetLoss}%";

        if (empty($masalah)) {
            return "Performa jaringan dalam kondisi {$kategori}. Semua parameter QoS berada dalam batas optimal. Tidak ditemukan masalah signifikan pada pengujian ini.";
        }

        $masalahStr = implode(', ', $masalah);
        return "Performa jaringan berkategori {$kategori}. Teridentifikasi masalah pada: {$masalahStr}. Disarankan untuk melakukan evaluasi dan optimasi pada aspek tersebut.";
    }
}
