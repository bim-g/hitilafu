# Hitilafu - Beautiful PHP Error Handler

A developer-friendly error page renderer inspired by Laravel Ignition, but standalone with no dependencies.

## Features

* Beautiful, modern error pages with dark/light theme
* Stack trace with code context
* Intelligent solution suggestions
* Request and environment information
* Responsive design
* Syntax-highlighted code blocks

## Installation

```bash
composer require wepesi/hitilafu
```

## Usage

```php
<?php
require_once 'vendor/autoload.php';

use Wepesi\Hitilafu\Hitilafu;

// Register the error handler
Hitilafu::register()
    ->setAppName('My Awesome App')
    ->setTheme('dark'); // or 'light'
```

## Custom Solutions

Add custom solutions for specific error patterns:

```php
Hitilafu::register()
    ->addSolution(
        'mysqli_connect',
        'MySQL Connection Issue',
        'Make sure your MySQL server is running and credentials are correct.',
        [
            ['text' => 'MySQL Docs', 'url' => 'https://dev.mysql.com/doc/']
        ]
    );
```
custom solutions are used based on the application implementation like (plugins, component, models, controllers, ...)

![image](/assets/Screenshot%202025-12-02%20135752.png)

## Important: Syntax Error Limitations

### Why Syntax Errors Can't Be Caught

**Syntax errors (parse errors) occur during PHP's compilation phase, BEFORE your code executes.** This means:

1. **Same file syntax errors**: Cannot be caught because PHP parses the entire file before running it
2. **Included file syntax errors**: CAN be caught via `handleShutdown()` when using `include` or `require`

### Example

**This WON'T work** (syntax error in main file):
```php
<?php
Hitilafu::register(); // Handler registered

echo "test"  // Syntax error - handler never runs because file fails to parse
```

**This WILL work** (syntax error in included file):
```php
<?php
// index.php
Hitilafu::register(); // Handler registered successfully

include 'bad_file.php'; // If bad_file.php has syntax errors, handler catches it
```

### Testing Syntax Error Handling

To test syntax error you can check the example file where the is a list of custom example already tested.

```php
<?php
Hitilafu::register();

// This will trigger the error handler
include 'example.php'; // Contains syntax errors
```

![example](/assets/Screenshot%202025-12-02%20153015.png)

## Error Types Handled

* **Runtime Errors**: Division by zero, undefined variables, etc.
* **Exceptions**: Uncaught exceptions
* **Fatal Errors**: Memory exhaustion, maximum execution time
* **Parse Errors**: In included files (NOT in the main file)
* **Warnings & Notices**: Converted to ErrorException

## Built-in Solutions

The handler provides intelligent solutions for common errors:

* File not found
* Database connection errors
* Undefined variables/functions
* Class not found
* Memory exhausted
* Permission denied
* And more...

## License

Apache2.0 License
