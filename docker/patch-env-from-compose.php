<?php

declare(strict_types=1);

$envPath = dirname(__DIR__).'/.env';

if (! is_readable($envPath)) {
    exit(0);
}

$keys = [
    'DB_HOST',
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_PASSWORD',
    'REDIS_HOST',
    'REDIS_PORT',
    'QUEUE_CONNECTION',
    'CACHE_STORE',
];

$lines = file($envPath, FILE_IGNORE_NEW_LINES) ?: [];
$seen = [];

foreach ($lines as $i => $line) {
    $trimmed = trim($line);
    if ($trimmed === '' || str_starts_with($trimmed, '#')) {
        continue;
    }

    if (! str_contains($line, '=')) {
        continue;
    }

    [$name] = explode('=', $line, 2);
    $name = trim($name);

    if (! in_array($name, $keys, true)) {
        continue;
    }

    $value = getenv($name);
    if ($value === false || $value === '') {
        continue;
    }

    $lines[$i] = $name.'='.$value;
    $seen[$name] = true;
}

foreach ($keys as $key) {
    if (isset($seen[$key])) {
        continue;
    }

    $value = getenv($key);
    if ($value === false || $value === '') {
        continue;
    }

    $lines[] = $key.'='.$value;
}

file_put_contents($envPath, implode(PHP_EOL, $lines).PHP_EOL);
