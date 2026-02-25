<?php
namespace App\Helpers;

class GradeHelper
{
    public static function hitungGrade(float $tersedia, float $terpakai): string
    {
        if ($tersedia <= 0)
            return 'D';
        $persen = ($terpakai / $tersedia) * 100;
        if ($persen >= 80)
            return 'A';
        if ($persen >= 60)
            return 'B';
        if ($persen >= 40)
            return 'C';
        return 'D';
    }

    public static function labelGrade(string $grade): string
    {
        return match ($grade) {
            'A' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup', 'D' => 'Kurang Baik', default => '-'
        };
    }

    public static function badgeGrade(string $grade): string
    {
        return match ($grade) {
            'A' => 'success', 'B' => 'info', 'C' => 'warning', 'D' => 'danger', default => 'secondary'
        };
    }

    public static function hitungPersentase(float $tersedia, float $terpakai): float
    {
        if ($tersedia <= 0)
            return 0;
        return round(($terpakai / $tersedia) * 100, 2);
    }

    public static function badgeStatus(string $status): string
    {
        return match ($status) {
            'UP', 'Normal' => 'success',
            'Degraded' => 'warning',
            'DOWN', 'Down' => 'danger',
            default => 'secondary'
        };
    }

    public static function gradesAll(): array
    {
        return [
            'A' => ['label' => 'Sangat Baik', 'min' => 80, 'color' => '#10b981'],
            'B' => ['label' => 'Baik', 'min' => 60, 'color' => '#06b6d4'],
            'C' => ['label' => 'Cukup', 'min' => 40, 'color' => '#f59e0b'],
            'D' => ['label' => 'Kurang Baik', 'min' => 0, 'color' => '#ef4444'],
        ];
    }
}
