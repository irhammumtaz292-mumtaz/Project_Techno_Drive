<?php

namespace App\Services;

use App\Models\Spk;
use App\Models\PurchaseOrder; // <-- Import model PurchaseOrder
use Carbon\Carbon;

class LetterNumberService
{
    // Generator untuk SPK
    public static function generateSpkNumber()
    {
        $year = Carbon::now()->format('Y');
        $romanMonth = self::toRoman(Carbon::now()->format('n'));
        
        $latestSpk = Spk::whereYear('created_at', $year)
                        ->orderBy('id', 'desc')
                        ->first();

        $nextNumber = 1;
        if ($latestSpk) {
            $parts = explode('/', $latestSpk->no_spk);
            if (isset($parts[0]) && is_numeric($parts[0])) {
                $nextNumber = intval($parts[0]) + 1;
            }
        }

        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        return "{$formattedNumber}/TD/SPK/{$romanMonth}/{$year}";
    }

    // Generator untuk PO (Purchase Order)
    public static function generatePoNumber()
    {
        $year = Carbon::now()->format('Y');
        $romanMonth = self::toRoman(Carbon::now()->format('n'));
        
        $latestPo = PurchaseOrder::whereYear('created_at', $year)
                                 ->orderBy('id', 'desc')
                                 ->first();

        $nextNumber = 1;
        if ($latestPo) {
            $parts = explode('/', $latestPo->no_po);
            if (isset($parts[0]) && is_numeric($parts[0])) {
                $nextNumber = intval($parts[0]) + 1;
            }
        }

        $formattedNumber = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        
        // Format PO: 001/TD/PO/XI/2026
        return "{$formattedNumber}/TD/PO/{$romanMonth}/{$year}";
    }

    private static function toRoman($number)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII', 
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[$number] ?? 'I';
    }
}