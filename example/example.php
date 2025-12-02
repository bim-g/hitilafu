<?php

echo "<h1> Error Handler is Active!</h1>";
echo "<p>The error handler is now registered and ready to catch errors.</p>";
echo "<p>Uncomment any of the examples below to see the beautiful error page in action!</p>";

// Example 1: Trigger a simple error
// echo "<h2>Example 1: Undefined Variable</h2>";
// Uncomment to test:
echo $undefinedVariable;

// Example 2: Trigger a file not found error
// echo "<h2>Example 2: File Not Found</h2>";
// Uncomment to test:
// include 'nonexistent_file.php';

// Example 3: Trigger a division by zero error
// echo "<h2>Example 3: Division by Zero</h2>";
// Uncomment to test:
// $result = 10 / 0;

// Example 4: Trigger a function not found error
// echo "<h2>Example 4: Undefined Function</h2>";
// Uncomment to test:
// nonExistentFunction();

// Example 5: Trigger a class not found error
// echo "<h2>Example 5: Class Not Found</h2>";
// Uncomment to test:
// $obj = new NonExistentClass();

// Example 6: Trigger a custom exception
// echo "<h2>Example 6: Custom Exception</h2>";
// Uncomment to test:
// throw new Exception('This is a custom exception message for testing purposes!');

// Example 7: Trigger a type error
// echo "<h2>Example 7: Type Error</h2>";
// Uncomment to test:
/*
function requiresString(string $param) {
    return strlen($param);
}
requiresString(123); // Passing integer instead of string
*/

// Example 8: Database connection error simulation
// echo "<h2>Example 8: Database Connection Error (Simulated)</h2>";
// Uncomment to test:
/*
try {
    $pdo = new PDO('mysql:host=wrong_host;dbname=wrong_db', 'wrong_user', 'wrong_pass');
} catch (PDOException $e) {
    throw $e;
}
*/

// Example 9: Array access error
// echo "<h2>Example 9: Array Access Error</h2>";
// Uncomment to test:
/*
$array = ['key1' => 'value1'];
echo $array['nonexistent_key']; // This will trigger a notice/warning
*/

// Example 10: Parse error (syntax error) - this needs to be in the actual code
// echo "<h2>Example 10: Syntax Error</h2>";
// This would need actual syntax error in the file, like:
// echo "missing semicolon"

// Uncomment this line to see the error handler in action:
// throw new Exception('This is a test exception to demonstrate the beautiful error handler! Try exploring the Stack Trace, Solutions, Request, and Environment tabs.');