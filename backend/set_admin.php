<?php

require_once 'vendor/autoload.php';

// 啟動 Laravel 應用
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "Setting admin@example.com as admin...\n";

// 查找用戶
$user = User::where('email', 'admin@example.com')->first();

if (!$user) {
    echo "User admin@example.com not found. Creating...\n";
    $user = User::create([
        'name' => 'admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('123'),
        'is_admin' => 1,
    ]);
    echo "User created successfully!\n";
} else {
    echo "User found. Setting as admin...\n";
    $user->is_admin = 1;
    $user->save();
    echo "User updated successfully!\n";
}

echo "User ID: " . $user->id . "\n";
echo "User Email: " . $user->email . "\n";
echo "Is Admin: " . ($user->is_admin ? 'Yes' : 'No') . "\n";

echo "Done!\n"; 