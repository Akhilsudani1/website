<?php
declare(strict_types=1);

/**
 * Quick test script to verify project setup
 * Run with: php test.php
 */

require __DIR__ . '/vendor/autoload.php';

$tests = [
    'Autoloader loaded' => function_exists('Composer\Autoload\ComposerStaticInit') ?? false,
];

// Test namespace classes
try {
    $asset = new \App\Domain\Entity\Asset('a1', 'Laptop', 'Electronics', 'available');
    $tests['Asset entity created'] = true;
} catch (Exception $e) {
    $tests['Asset entity created'] = false;
}

try {
    $user = new \App\Domain\Entity\User('u1', 'test@test.com', 'hashedpwd', 'admin');
    $tests['User entity created'] = true;
} catch (Exception $e) {
    $tests['User entity created'] = false;
}

try {
    $checkout = new \App\Domain\Entity\Checkout('c1', 'a1', 'u1', '2024-02-09 10:00:00');
    $tests['Checkout entity created'] = true;
} catch (Exception $e) {
    $tests['Checkout entity created'] = false;
}

try {
    $dto = new \App\Domain\DTO\CreateAssetDTO('Test', 'Category');
    $tests['CreateAssetDTO created'] = true;
} catch (Exception $e) {
    $tests['CreateAssetDTO created'] = false;
}

try {
    $log = new \App\Notification\LogNotificationChannel();
    $tests['LogNotificationChannel created'] = true;
} catch (Exception $e) {
    $tests['LogNotificationChannel created'] = false;
}

try {
    $email = new \App\Notification\EmailNotificationChannel();
    $tests['EmailNotificationChannel created'] = true;
} catch (Exception $e) {
    $tests['EmailNotificationChannel created'] = false;
}

try {
    \App\Domain\Validation\Validator::required('test', 'Test field');
    $tests['Validator works'] = true;
} catch (Exception $e) {
    $tests['Validator works'] = false;
}

// Display results
echo "\n========== PROJECT VERIFICATION ==========\n\n";

$passed = 0;
$failed = 0;

foreach ($tests as $name => $result) {
    $status = $result ? '✓ PASS' : '✗ FAIL';
    echo "{$status}: {$name}\n";
    if ($result) $passed++; else $failed++;
}

echo "\n==========================================\n";
echo "Passed: {$passed} / Failed: {$failed}\n";
echo "Status: " . ($failed === 0 ? '✓ ALL TESTS PASSED' : '✗ SOME TESTS FAILED') . "\n";
echo "==========================================\n\n";

exit($failed === 0 ? 0 : 1);
