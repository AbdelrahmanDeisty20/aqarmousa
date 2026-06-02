<?php

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('role', 'admin')->first();

if (!$user) {
    echo "No admin user found\n";
    exit;
}

echo "Found admin: {$user->email} (ID: {$user->id})\n";

$countBefore = DB::table('notifications')->count();
echo "Count before: $countBefore\n";

try {
    echo "\n--- Testing notifyNow() with Filament Notification ---\n";

    $filamentNotification = Notification::make()
        ->title('NotifyNow Test ' . time())
        ->success();

    // We get the DatabaseNotification object and send it NOW
    $user->notifyNow($filamentNotification->toDatabase());

    echo "notifyNow() called.\n";

    $countAfter = DB::table('notifications')->count();
    echo "Count after: $countAfter\n";

    if ($countAfter > $countBefore) {
        echo "SUCCESS: Notification saved IMMEDIATELY to database!\n";
    } else {
        echo "FAILURE: Still not saved immediately.\n";
    }
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
