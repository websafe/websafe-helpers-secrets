<?php

/**
 * MIT License
 *
 * Copyright (c) 2025 Thomas Szteliga <ts@websafe.pl>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

declare(strict_types=1);

namespace Websafe\Helpers;

/**
 * Resolve a secret with a portable strategy:
 *  1) Environment variable (CI/CD, Docker, shell, systemd, etc.)
 *  2) Local dev file: ./secrets/NAME
 *  3) Container file: /run/secrets/NAME (Docker/Swarm/K8s)
 *  4) Default value (if provided)
 *
 * Usage:
 *   use function Websafe\Helpers\secret;
 *   $dbPass = secret('DB_PASSWORD', true);
 *   $apiToken = secret('API_TOKEN');
 *   $logLevel = secret('LOG_LEVEL', false, 'info');
 *
 * @param string $name Secret name
 * @param bool $required Throw if not found
 * @param string $default Fallback when not required
 * @return string
 */
function secret(
    string $name,
    bool $required = false,
    string $default = ''
): string {
    $v = getenv($name);
    if ($v !== false && $v !== '') {
        return $v;
    }

    // Local dev fallback: ./secrets/NAME (project root)
    $local = __DIR__ . '/../secrets/' . $name;
    if (is_readable($local)) {
        $c = trim((string) @file_get_contents($local));
        if ($c !== '') {
            return $c;
        }
    }

    // Container secrets: /run/secrets/NAME
    $file = '/run/secrets/' . $name;
    if (is_readable($file)) {
        $c = trim((string) @file_get_contents($file));
        if ($c !== '') {
            return $c;
        }
    }

    if ($required && $default === '') {
        throw new \RuntimeException(
            "Missing required secret: {$name}"
        );
    }

    return $default;
}
