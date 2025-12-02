<?php

namespace Wepesi\Hitilafu;

use Exception;

interface HitilafuContract
{
    public static function register(): HitilafuContract;
    public function setTheme(string $theme = 'dark'): HitilafuContract;
    public function setAppName(string $name): HitilafuContract;
    public function addSolution(string $pattern, string $title, string $description, array $links = []): HitilafuContract;
    public function handleError(int $errno, string $errstr, ?string $errfile = null, ?int $errline = null);
    public function handleException(Exception $exception);
    public function handleShutdown();
}