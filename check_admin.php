<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$notifications = \Illuminate\Support\Facades\DB::table('notifications')
    ->where('notifiable_type', 'App\Models\User')
    ->where('notifiable_id', 1)
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

echo "Notifications found: " . $notifications->count() . PHP_EOL;
foreach ($notifications as $notification) {
    echo "ID: " . $notification->id . " | Type: " . $notification->type . " | Created: " . $notification->created_at . PHP_EOL;
    echo "Data: " . $notification->data . PHP_EOL;
}
