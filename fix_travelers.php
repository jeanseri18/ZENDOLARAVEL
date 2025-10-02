<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

// Get travelers with empty license plates
$travelers = DB::table('travelers')->where('license_plate', '')->get();

echo "Found " . $travelers->count() . " travelers with empty license plates\n";

// Update each traveler with a unique license plate
foreach ($travelers as $traveler) {
    $newLicensePlate = 'TEMP-' . str_pad($traveler->traveler_id, 6, '0', STR_PAD_LEFT);
    
    // Use raw SQL to avoid triggering other constraints
    DB::statement('UPDATE travelers SET license_plate = ? WHERE traveler_id = ?', [
        $newLicensePlate,
        $traveler->traveler_id
    ]);
    
    echo "Updated traveler {$traveler->traveler_id} with license plate: {$newLicensePlate}\n";
}

echo "Done!\n";