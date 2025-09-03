# Websafe Helpers — Secrets

Tiny helper for reading secrets in PHP projects, with a portable
resolution order:

1. Environment variable (CI/CD, Docker, systemd, shell, .env via
   Dotenv)
2. Local dev file: `./secrets/NAME`
3. Container file: `/run/secrets/NAME` (Docker/Swarm/Kubernetes)
4. Default value

## Install

```bash
composer require websafe/helpers-secrets
```

## Use

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use function Websafe\Helpers\secret;

$api = secret('API_TOKEN', true);
$pwd = secret('DB_PASSWORD', true);
$lv  = secret('LOG_LEVEL', false, 'info');
```
