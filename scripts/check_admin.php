<?php
// Script to bootstrap Laravel and check admin users
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$emails = ['y@gmail.com','s@gmail.com','admin1@gmail.com','admin2@gmail.com'];
$rows = User::whereIn('email', $emails)->get();

if ($rows->isEmpty()) {
    echo "NO MATCH\n";
} else {
    foreach ($rows as $r) {
        $ok = Hash::check('12345678', $r->password) ? 'YES' : 'NO';
        echo "{$r->email} => " . ($r->role ?? '(no role)') . " | HASHOK: {$ok}\n";
    }
}

// Also show total admins
$admins = User::whereIn('role', ['admin','admin2'])->get();
echo "\nAdmin users found: " . $admins->count() . "\n";
foreach($admins as $a){ echo " - {$a->email} ({$a->role})\n"; }
