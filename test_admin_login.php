<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$admin = \App\Models\Admin::where('email', 'admin@tourism.com')->first();

if ($admin) {
    echo "Admin found: " . $admin->name . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Password hash: " . substr($admin->password, 0, 60) . "\n";
    echo "Check password123: " . (\Illuminate\Support\Facades\Hash::check('password123', $admin->password) ? 'TRUE' : 'FALSE') . "\n";

    // Test Auth attempt
    $credentials = ['email' => 'admin@tourism.com', 'password' => 'password123'];
    $result = \Illuminate\Support\Facades\Auth::guard('admin')->attempt($credentials);
    echo "Auth attempt result: " . ($result ? 'TRUE' : 'FALSE') . "\n";
} else {
    echo "Admin not found!\n";
}
