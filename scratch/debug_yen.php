<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\SalaryMechanism;

$user = User::where('email', 'user012@cafe.com')->first();
if ($user) {
    echo "User: {$user->name}, Position: {$user->getPositionName()}\n";
    echo "isManagerSalary: " . ($user->isManagerSalary() ? 'YES' : 'NO') . "\n";
    echo "isAdmin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
    
    $mech = SalaryMechanism::where('user_id', $user->id)->first();
    if ($mech) {
        echo "Mechanism Salary: {$mech->salary}\n";
    } else {
        echo "No Mechanism Found\n";
    }
} else {
    echo "User not found\n";
}
