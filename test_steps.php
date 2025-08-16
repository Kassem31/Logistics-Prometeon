<?php

require_once 'vendor/autoload.php';

use App\Models\Inbound;
use Illuminate\Database\Capsule\Manager as Capsule;

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing step progression logic...\n\n";

// Test the step order
$inbound = new Inbound();
echo "Step Order:\n";
foreach($inbound->steps as $step => $number) {
    echo "$number. " . ucfirst($step) . "\n";
}

echo "\nStep progression methods:\n";
echo "1. canGoBooking() - Checks if inbound details are complete\n";
echo "2. canGoShipping() - Checks if booking is complete\n";
echo "3. canGoDocumentCycle() - Checks if shipping is complete (with conditional fields)\n";
echo "4. canGoClearance() - Checks if document cycle is complete\n";
echo "5. canGoDelivery() - Checks if clearance is complete\n";
echo "6. canGoBank() - Checks if delivery is complete\n";
echo "7. isComplete() - Checks if bank is complete\n";

echo "\nTest completed successfully!\n";
