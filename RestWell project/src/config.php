<?php
/**
 * Configuration and Environment Loader for RestWell
 * Loads environment variables from .env if present.
 */

if (!function_exists('loadRestWellEnv')) {
    function loadRestWellEnv($envFile = null) {
        if ($envFile === null) {
            $candidates = [
                __DIR__ . '/../.env',
                __DIR__ . '/.env',
                dirname(__DIR__) . '/.env',
                dirname(__DIR__, 2) . '/.env'
            ];
            foreach ($candidates as $candidate) {
                if (file_exists($candidate) && is_readable($candidate)) {
                    $envFile = $candidate;
                    break;
                }
            }
        }

        if ($envFile && file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, ';')) {
                    continue;
                }
                if (strpos($line, '=') !== false) {
                    list($key, $val) = explode('=', $line, 2);
                    $key = trim($key);
                    $val = trim($val);

                    // Strip wrapping quotes
                    if ((str_starts_with($val, '"') && str_ends_with($val, '"')) ||
                        (str_starts_with($val, "'") && str_ends_with($val, "'"))) {
                        $val = substr($val, 1, -1);
                    }

                    if (!array_key_exists($key, $_ENV)) {
                        $_ENV[$key] = $val;
                    }
                    if (!array_key_exists($key, $_SERVER)) {
                        $_SERVER[$key] = $val;
                    }
                    putenv("{$key}={$val}");
                }
            }
        }
    }
}

if (!function_exists('getEnvValue')) {
    function getEnvValue($key, $default = '') {
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }
        $val = getenv($key);
        if ($val !== false && $val !== '') {
            return $val;
        }
        if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') {
            return $_SERVER[$key];
        }
        return $default;
    }
}

// Automatically load environment variables
loadRestWellEnv();
