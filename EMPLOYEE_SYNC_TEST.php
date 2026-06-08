<?php

/**
 * Employee Sync Testing Script
 *
 * Usage:
 * php artisan tinker < EMPLOYEE_SYNC_TEST.php
 *
 * Or run interactively:
 * php artisan tinker
 * >>> include 'EMPLOYEE_SYNC_TEST.php';
 */

echo "=== Employee Sync Test Suite ===\n\n";

// ============================================================
// TEST 1: Check Bridge Key Configuration
// ============================================================
echo "TEST 1: Bridge Key Configuration\n";
echo "-----------------------------------\n";

$bridgeKey = env('HELLOTRANSPORT_BRIDGE_SHARED_KEY');
if ($bridgeKey) {
    echo "✓ HELLOTRANSPORT_BRIDGE_SHARED_KEY is set\n";
    echo "  Value (first 20 chars): " . substr($bridgeKey, 0, 20) . "...\n";
} else {
    echo "✗ HELLOTRANSPORT_BRIDGE_SHARED_KEY is NOT set in .env\n";
}
echo "\n";

// ============================================================
// TEST 2: Check Migration Applied
// ============================================================
echo "TEST 2: Migration - hr_employee_id Column\n";
echo "-------------------------------------------\n";

$columns = \Illuminate\Support\Facades\Schema::getColumnListing('user');
if (in_array('hr_employee_id', $columns)) {
    echo "✓ hr_employee_id column exists in user table\n";

    // Check if unique
    $columnDetails = \Illuminate\Support\Facades\DB::select(
        "SELECT COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='user' AND COLUMN_NAME='hr_employee_id' LIMIT 1"
    );
    if (!empty($columnDetails) && $columnDetails[0]->COLUMN_KEY === 'UNI') {
        echo "✓ hr_employee_id has unique constraint\n";
    } else {
        echo "⚠ hr_employee_id may not have unique constraint\n";
    }
} else {
    echo "✗ hr_employee_id column NOT FOUND in user table\n";
    echo "  Run: php artisan migrate\n";
}
echo "\n";

// ============================================================
// TEST 3: Check Controller Exists
// ============================================================
echo "TEST 3: EmployeeSyncController Exists\n";
echo "--------------------------------------\n";

$controllerPath = app_path('Http/Controllers/Bridge/EmployeeSyncController.php');
if (file_exists($controllerPath)) {
    echo "✓ EmployeeSyncController.php exists\n";
    echo "  Path: app/Http/Controllers/Bridge/EmployeeSyncController.php\n";
} else {
    echo "✗ EmployeeSyncController.php NOT FOUND\n";
}
echo "\n";

// ============================================================
// TEST 4: Check Route Registered
// ============================================================
echo "TEST 4: Route Registration\n";
echo "---------------------------\n";

$routes = \Illuminate\Support\Facades\Route::getRoutes();
$employeeSyncRoute = null;

foreach ($routes as $route) {
    if (strpos($route->getPath(), 'bridge/employee/sync') !== false) {
        $employeeSyncRoute = $route;
        break;
    }
}

if ($employeeSyncRoute) {
    echo "✓ /bridge/employee/sync route registered\n";
    echo "  Methods: " . implode(', ', $employeeSyncRoute->methods) . "\n";
    echo "  Controller: " . $employeeSyncRoute->getActionName() . "\n";
} else {
    echo "✗ /bridge/employee/sync route NOT FOUND\n";
    echo "  Make sure route is registered in routes/web.php\n";
}
echo "\n";

// ============================================================
// TEST 5: Test User Model - Fillable
// ============================================================
echo "TEST 5: User Model Fillable Array\n";
echo "-----------------------------------\n";

$user = new \App\User();
$fillable = $user->getFillable();

$requiredFields = ['hr_employee_id', 'phone', 'role', 'verify', 'status'];
$missing = [];

foreach ($requiredFields as $field) {
    if (in_array($field, $fillable)) {
        echo "✓ '$field' in User::$fillable\n";
    } else {
        echo "✗ '$field' NOT in User::$fillable\n";
        $missing[] = $field;
    }
}

if (!empty($missing)) {
    echo "\nUpdate app/User.php and add missing fields to \$fillable\n";
}
echo "\n";

// ============================================================
// TEST 6: Check Role Model
// ============================================================
echo "TEST 6: Role Model Configuration\n";
echo "----------------------------------\n";

$role = new \App\role();
$fillable = $role->getFillable();

echo "Role::$fillable = " . json_encode($fillable) . "\n";

if (in_array('name', $fillable) && in_array('slug', $fillable)) {
    echo "✓ Role model can be created with name and slug\n";
} else {
    echo "✗ Role model missing required fillable fields\n";
}
echo "\n";

// ============================================================
// TEST 7: Sample Database Queries
// ============================================================
echo "TEST 7: Database Sample Queries\n";
echo "--------------------------------\n";

// Count users with hr_employee_id set
$usersWithHrId = \App\User::whereNotNull('hr_employee_id')->count();
echo "Users with hr_employee_id: " . $usersWithHrId . "\n";

// Count total roles
$roleCount = \App\role::count();
echo "Total roles: " . $roleCount . "\n";

// Count total users
$userCount = \App\User::count();
echo "Total users: " . $userCount . "\n";

// Sample role
$sampleRole = \App\role::first();
if ($sampleRole) {
    echo "Sample role: ID=" . $sampleRole->id . ", Name=" . $sampleRole->name . ", Slug=" . $sampleRole->slug . "\n";
} else {
    echo "No roles found in database\n";
}
echo "\n";

// ============================================================
// TEST 8: Simulate Sync Request
// ============================================================
echo "TEST 8: Simulate Sync Request (Dry Run)\n";
echo "----------------------------------------\n";

$testPayload = [
    'employee_id' => 999,
    'first_name'  => 'Test',
    'last_name'   => 'Sync',
    'email'       => 'test.sync.999@example.com',
    'phone'       => '1234567890',
    'role_id'     => 1,
    'role_name'   => 'Test Agent',
];

echo "Test Payload:\n";
echo json_encode($testPayload, JSON_PRETTY_PRINT) . "\n";

// Validate
$validator = \Illuminate\Support\Facades\Validator::make($testPayload, [
    'employee_id' => ['required', 'integer'],
    'first_name'  => ['required', 'string', 'max:50'],
    'last_name'   => ['required', 'string', 'max:50'],
    'email'       => ['required', 'email', 'max:50'],
    'phone'       => ['nullable', 'string', 'max:20'],
    'role_id'     => ['required', 'integer'],
    'role_name'   => ['required', 'string', 'max:50'],
]);

if ($validator->passes()) {
    echo "✓ Payload validation passed\n";
} else {
    echo "✗ Payload validation failed:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "  - " . $error . "\n";
    }
}
echo "\n";

// ============================================================
// TEST 9: Check Logs
// ============================================================
echo "TEST 9: Recent Log Entries\n";
echo "---------------------------\n";

$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $lines = array_slice(file($logFile), -20); // Last 20 lines
    $relevantLines = array_filter($lines, function($line) {
        return strpos($line, 'EmployeeSyncController') !== false ||
               strpos($line, 'EmployeeObserver') !== false ||
               strpos($line, 'employee') !== false;
    });

    if (!empty($relevantLines)) {
        echo "Recent relevant log entries:\n";
        foreach (array_slice($relevantLines, -5) as $line) {
            echo "  " . trim($line) . "\n";
        }
    } else {
        echo "No employee sync logs found yet\n";
    }
} else {
    echo "Log file not found: " . $logFile . "\n";
}
echo "\n";

// ============================================================
// TEST 10: Helper Functions
// ============================================================
echo "TEST 10: Helper Functions\n";
echo "-------------------------\n";

echo "To test the sync endpoint, use curl:\n";
echo "curl -X POST https://hellotransport.com/bridge/employee/sync \\\n";
echo "  -H 'X-Bridge-Key: " . substr($bridgeKey ?? 'YOUR_KEY', 0, 20) . "...' \\\n";
echo "  -H 'Content-Type: application/json' \\\n";
echo "  -d '{\"employee_id\": 1, \"first_name\": \"John\", \"last_name\": \"Doe\", \"email\": \"john@example.com\", \"phone\": \"1234567890\", \"role_id\": 1, \"role_name\": \"Agent\"}'\n";
echo "\n";

echo "To manually trigger sync in HR:\n";
echo "cd d:\\laragon2\\www\\washinton_hr\n";
echo "php artisan tinker\n";
echo ">>> \$emp = \\App\\Models\\Employee::find(1);\n";
echo ">>> \$emp->touch(); // Triggers observer\n";
echo "\n";

echo "=== End of Test Suite ===\n";
