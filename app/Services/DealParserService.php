<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DealParserService
{
    public function parse(string $dealName): array
    {
        // Normalize dashes and spacing
        $dealName = preg_replace('/[\x{2013}\x{2014}\x{2012}\x{2011}]/u', '-', $dealName); // normalize various dashes to -

        $dealName = preg_replace('/[^\S\r\n]+/', ' ', $dealName); // collapse weird/invisible spaces to normal space
        
        $dealName = trim($dealName);

        // log the cleaned result for sanity check
        Log::info('Normalized Deal Name:', ['name' => $dealName]);

        preg_match('/^(S1|S2|MI|TBC)\s*-\s*(?:\w+:)?\s*(.*?)\((\d+)\s*Pax\)\s*-\s*(\d{1,2})\s+(\w+)\s+(\d{2})\s*\/\s*(\d+)N/i', $dealName, $matches);

        if (!$matches) {
        
            throw new \Exception("Failed to parse deal name: $dealName");
        
        }

        [$full, $boatCode, $guestName, $guestCount, $day, $month, $year, $nights] = $matches;

        $boatCode = strtoupper($boatCode);

        return [
        
            'boat_code' => $boatCode,
        
            'boat_name' => [
        
                'S1' => 'Samara 1',
        
                'S2' => 'Samara 2',
        
                'MI' => 'Mischief',
        
                'TBC' => 'TBC',
        
            ][$boatCode] ?? 'Unknown Boat',
        
                'guest_name' => trim($guestName),
        
                'guest_count' => (int) $guestCount,
        
                'start_date' => \Carbon\Carbon::createFromFormat('d M y', "$day $month $year"),
        
                'nights' => (int) $nights,
        
            ];
    }
}
