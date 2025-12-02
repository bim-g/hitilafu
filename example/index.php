<?php

/**
 * Hitilafu Error Handler Example
 * 
 * This file demonstrates how to use the error handler in your PHP application
 */

use Wepesi\Hitilafu\Hitilafu;

require_once __DIR__ . '/../vendor/autoload.php';

// Register the error handler and configure it

Hitilafu::register()
    ->setAppName('My Awesome App')
    // ->setTheme('dark') // default is 'light'
    // Add custom solutions for specific errors
    ->addSolution(
        'mysqli_connect',
        'MySQL Connection Issue',
        'Make sure your MySQL server is running and the credentials in your configuration file are correct. Check if the MySQL port (default 3306) is open and accessible.',
        [
            ['text' => 'MySQL Connection Guide', 'url' => 'https://dev.mysql.com/doc/refman/8.0/en/connecting.html'],
            ['text' => 'Common Connection Errors', 'url' => 'https://dev.mysql.com/doc/mysql-errors/8.0/en/server-error-reference.html']
        ]
    )
    ->addSolution(
        'Composer\Autoload',
        'Composer Autoload Issue',
        'Run "composer install" or "composer dump-autoload" to regenerate the autoloader. Make sure composer.json is properly configured.',
        [
            ['text' => 'Composer Documentation', 'url' => 'https://getcomposer.org/doc/']
        ]
    )
    ->addSolution(
        'redis',
        'Redis Connection Failed',
        'Ensure Redis server is running on the specified host and port. Check if Redis is installed and the service is active. Verify firewall rules allow connection to Redis port (default 6379).',
        [
            ['text' => 'Redis Quick Start', 'url' => 'https://redis.io/docs/getting-started/']
        ]
    );

// include a file with syntax errors to test parse error handling
include 'example.php';