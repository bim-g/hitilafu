<?php

namespace Wepesi\Hitilafu;

/**
 * Beautiful Error Handler - Developer-Friendly Error Page Renderer
 * Inspired by Laravel Ignition but standalone with no dependencies
 */

use ErrorException;
use Exception;

class Hitilafu implements HitilafuContract
{
    private static $instance = null;
    private array $errorData;
    private array $customSolutions;
    private string $theme;
    private string $appName;
    private string $appPath;

    private function __construct()
    {
        $this->appPath = dirname(__FILE__);
        $this->errorData = [];
        $this->customSolutions = [];
        $this->theme = 'dark';
        $this->appName = 'Hitilafu';
    }

    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register the error handler
     */
    public static function register(): HitilafuContract
    {
        $instance = self::getInstance();

        set_error_handler([$instance, 'handleError']);
        set_exception_handler([$instance, 'handleException']);
        register_shutdown_function([$instance, 'handleShutdown']);

        return $instance;
    }

    /**
     * Set custom theme
     * @param string $theme 'dark' or 'light'
     * @return HitilafuContract
     */
    public function setTheme(string $theme = 'dark'): HitilafuContract
    {
        $this->theme = in_array($theme, ['dark', 'light']) ? $theme : 'dark';
        return $this;
    }

    /**
     * Set application name
     * @param string $name
     * @return HitilafuContract
     */
    public function setAppName(string $name): HitilafuContract
    {
        $this->appName = $name;
        return $this;
    }

    /**
     * Add custom solution for specific error patterns
     * @param string $pattern Regex pattern to match error message or file
     * @param string $title Title of the solution
     * @param string $description Description of the solution
     * @param array $links Array of ['text' => '', 'url' => ''] for additional resources
     * @return HitilafuContract
     * 
     */
    public function addSolution(string $pattern, string $title, string $description, array $links = []): HitilafuContract
    {
        $this->customSolutions[] = [
            'pattern' => $pattern,
            'title' => $title,
            'description' => $description,
            'links' => $links
        ];
        return $this;
    }

    /**
     * Handle regular PHP errors
     * @param int $errno
     * @param string $errstr
     * @param string|null $errfile
     * @param int|null $errline
     * @return void
     */
    public function handleError(int $errno, string $errstr, ?string $errfile = null, ?int $errline = null)
    {
        $exception = new ErrorException($errstr, 0, $errno, $errfile, $errline);
        $this->handleException($exception);
    }

    /**
     * Handle uncaught exceptions
     * @param Exception $exception
     * @return void
     */
    public function handleException(Exception $exception)
    {
        $this->errorData = $this->gatherErrorData($exception);
        $this->render();
        exit(1);
    }

    /**
     * Handle fatal errors on shutdown
     * 
     * IMPORTANT: Parse errors (syntax errors) in the main file cannot be caught
     * because they occur during PHP's compilation phase, before this handler is registered.
     * However, parse errors in files loaded via include/require CAN be caught.
     * 
     * To test parse error handling, create a separate file with syntax errors
     * and include it after registering the handler.
     */
    public function handleShutdown()
    {
        $error = error_get_last();

        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $exception = new ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            );

            $this->handleException($exception);
        }
    }

    /**
     * Gather all error data
     */
    private function gatherErrorData($exception)
    {
        return [
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $exception->getCode(),
            'trace' => $this->parseStackTrace($exception),
            'context' => $this->getFileContext($exception->getFile(), $exception->getLine()),
            'request' => $this->getRequestData(),
            'environment' => $this->getEnvironmentData(),
            'solutions' => $this->getSolutions($exception)
        ];
    }

    /**
     * Parse stack trace
     */
    private function parseStackTrace($exception)
    {
        $trace = $exception->getTrace();
        $parsedTrace = [];

        // Add the exception point as first trace item
        array_unshift($trace, [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'function' => '{main}',
            'class' => '',
            'type' => ''
        ]);

        foreach ($trace as $index => $item) {
            $file = isset($item['file']) ? $item['file'] : '[internal function]';
            $line = isset($item['line']) ? $item['line'] : 0;

            $parsedTrace[] = [
                'index' => $index,
                'file' => $file,
                'line' => $line,
                'function' => $item['function'] ?? '',
                'class' => $item['class'] ?? '',
                'type' => $item['type'] ?? '',
                'args' => $this->formatArgs($item['args'] ?? []),
                'context' => $file !== '[internal function]' ? $this->getFileContext($file, $line, 3) : []
            ];
        }

        return $parsedTrace;
    }

    /**
     * Get file context around error line
     */
    private function getFileContext($file, $line, $context = 10)
    {
        if (!file_exists($file)) {
            return [];
        }

        $lines = file($file);
        $start = max(0, $line - $context - 1);
        $end = min(count($lines), $line + $context);

        $context = [];
        for ($i = $start; $i < $end; $i++) {
            $context[] = [
                'number' => $i + 1,
                'content' => rtrim($lines[$i]),
                'highlight' => ($i + 1) === $line
            ];
        }

        return $context;
    }

    /**
     * Format function arguments
     */
    private function formatArgs($args)
    {
        $formatted = [];

        foreach ($args as $arg) {
            if (is_object($arg)) {
                $formatted[] = get_class($arg);
            } elseif (is_array($arg)) {
                $formatted[] = 'Array(' . count($arg) . ')';
            } elseif (is_string($arg)) {
                $formatted[] = '"' . (strlen($arg) > 50 ? substr($arg, 0, 50) . '...' : $arg) . '"';
            } elseif (is_bool($arg)) {
                $formatted[] = $arg ? 'true' : 'false';
            } elseif (is_null($arg)) {
                $formatted[] = 'null';
            } else {
                $formatted[] = (string)$arg;
            }
        }

        return implode(', ', $formatted);
    }

    /**
     * Get request data
     */
    private function getRequestData()
    {
        return [
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'CLI',
            'url' => $this->getCurrentUrl(),
            'get' => $_GET,
            'post' => $_POST,
            'files' => $_FILES,
            'cookies' => $_COOKIE,
            'headers' => $this->getHeaders(),
            'session' => isset($_SESSION) ? $_SESSION : []
        ];
    }

    /**
     * Get current URL
     */
    private function getCurrentUrl()
    {
        if (php_sapi_name() === 'cli') {
            return 'CLI: ' . implode(' ', $_SERVER['argv'] ?? []);
        }

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        return $protocol . '://' . $host . $uri;
    }

    /**
     * Get HTTP headers
     */
    private function getHeaders()
    {
        $headers = [];

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            foreach ($_SERVER as $key => $value) {
                if (substr($key, 0, 5) === 'HTTP_') {
                    $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
                    $headers[$header] = $value;
                }
            }
        }

        return $headers;
    }

    /**
     * Get environment data
     */
    private function getEnvironmentData()
    {
        return [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'os' => PHP_OS,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'loaded_extensions' => get_loaded_extensions(),
            'timezone' => date_default_timezone_get(),
            'error_reporting' => error_reporting(),
            'display_errors' => ini_get('display_errors')
        ];
    }

    /**
     * Get solution suggestions
     */
    private function getSolutions($exception)
    {
        $solutions = [];
        $message = $exception->getMessage();
        $file = $exception->getFile();

        // Check custom solutions first
        foreach ($this->customSolutions as $solution) {
            if (
                preg_match('/' . $solution['pattern'] . '/i', $message) ||
                preg_match('/' . $solution['pattern'] . '/i', $file)
            ) {
                $solutions[] = $solution;
            }
        }

        // Built-in solution suggestions
        $builtInSolutions = $this->getBuiltInSolutions($exception);
        $solutions = array_merge($solutions, $builtInSolutions);

        return $solutions;
    }

    /**
     * Get built-in solution suggestions
     */
    private function getBuiltInSolutions($exception)
    {
        $customSolutions = [];
        $message = strtolower($exception->getMessage());

        // File not found errors
        if (
            strpos($message, 'no such file') !== false ||
            strpos($message, 'failed to open stream') !== false ||
            strpos($message, 'file not found') !== false
        ) {
            $customSolutions[] = $this->setSolutions('File Not Found', 'The file you\'re trying to access doesn\'t exist. Check the file path and ensure the file exists in the expected location. Verify file permissions and that the path is correct.', 'PHP File System Functions', 'https://www.php.net/manual/en/ref.filesystem.php');
        }

        // Database connection errors
        if (
            strpos($message, 'connection') !== false &&
            (strpos($message, 'database') !== false || strpos($message, 'mysql') !== false || strpos($message, 'pgsql') !== false)
        ) {
            $customSolutions[] = $this->setSolutions('Database Connection Failed', 'Unable to connect to the database. Check your database credentials, ensure the database server is running, and verify that the host and port are correct. Also check firewall settings.', 'PDO Documentation', 'https://www.php.net/manual/en/book.pdo.php');
        }

        // Undefined variable
        if (strpos($message, 'undefined variable') !== false) {
            $customSolutions[] = $this->setSolutions('Undefined Variable', 'You\'re trying to use a variable that hasn\'t been defined yet. Initialize the variable before using it, or check for typos in the variable name.', 'PHP Variables', 'https://www.php.net/manual/en/language.variables.php');
        }

        // Undefined function
        if (strpos($message, 'undefined function') !== false || strpos($message, 'call to undefined function') !== false) {
            $customSolutions[] = $this->setSolutions('Undefined Function', 'The function you\'re calling doesn\'t exist. Check for typos, ensure the function is defined before calling it, or verify that the required extension is loaded.', 'PHP Functions', 'https://www.php.net/manual/en/language.functions.php');
        }

        // Class not found
        if (strpos($message, 'class') !== false && strpos($message, 'not found') !== false) {
            $customSolutions[] = $this->setSolutions('Class Not Found', 'The class you\'re trying to use doesn\'t exist or isn\'t loaded. Check the class name for typos, ensure the file containing the class is included, or verify your autoloader configuration.', 'PHP Autoloading', 'https://www.php.net/manual/en/language.oop5.autoload.php');
        }

        // Syntax errors
        if (strpos($message, 'syntax error') !== false || strpos($message, 'parse error') !== false) {
            $customSolutions[] = $this->setSolutions('Syntax Error', 'There\'s a syntax error in your code. Check for missing semicolons, unmatched brackets, quotes, or parentheses. Review the code around the error line carefully.', 'PHP Syntax', 'https://www.php.net/manual/en/language.basic-syntax.php');
        }

        // Memory exhausted
        if (strpos($message, 'memory') !== false && strpos($message, 'exhausted') !== false) {
            $customSolutions[] = $this->setSolutions('Memory Limit Exceeded', 'Your script has exceeded the memory limit. Increase the memory_limit in php.ini, optimize your code to use less memory, or process data in smaller chunks.', 'Memory Management', 'https://www.php.net/manual/en/ini.core.php#ini.memory-limit');
        }

        // Permission denied
        if (strpos($message, 'permission denied') !== false) {
            $customSolutions[] = $this->setSolutions('Permission Denied', 'The file or directory doesn\'t have the correct permissions. Check file permissions using chmod/chown, ensure the web server user has access, and verify directory ownership.', 'File Permissions', 'https://www.php.net/manual/en/function.chmod.php');
        }

        // Maximum execution time
        if (strpos($message, 'maximum execution time') !== false) {
            $customSolutions[] = $this->setSolutions('Execution Time Exceeded', 'Your script took too long to execute. Increase max_execution_time in php.ini, optimize slow operations, or use set_time_limit() for long-running tasks.', 'Execution Time', 'https://www.php.net/manual/en/function.set-time-limit.php');
        }

        return $customSolutions;
    }

    private function setSolutions(string $title, string $description, string $linksText, string $linkURL = ''): array
    {
        return [
            'title' => $title,
            'description' => $description,
            'links' => [
                ['text' => $linksText, 'url' => $linkURL]
            ]
        ];
    }

    /**
     * Render the error page
     */
    private function render()
    {
        // Set appropriate headers
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/html; charset=UTF-8');
        }

        include __DIR__ . '/ErrorTemplate.php';
    }
}