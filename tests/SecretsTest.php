<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use function Websafe\Helpers\secret;

final class SecretsTest extends TestCase
{
    private string $repoRoot;
    private string $secretsDir;

    protected function setUp(): void
    {
        $this->repoRoot  = realpath(__DIR__ . '/..') ?: __DIR__ . '/..';
        $this->secretsDir = $this->repoRoot . '/secrets';
        if (!is_dir($this->secretsDir)) {
            mkdir($this->secretsDir, 0775, true);
        }

        // Clean env between tests
        putenv('DB_PASSWORD');
        putenv('API_TOKEN');
        putenv('LOG_LEVEL');

        // Clean files
        @unlink($this->secretsDir . '/DB_PASSWORD');
        @unlink($this->secretsDir . '/API_TOKEN');
        @unlink($this->secretsDir . '/LOG_LEVEL');
    }

    public function testEnvBeatsFiles(): void
    {
        file_put_contents($this->secretsDir . '/DB_PASSWORD', 'fromfile');
        putenv('DB_PASSWORD=fromenv');

        $val = secret('DB_PASSWORD');
        $this->assertSame('fromenv', $val);
    }

    public function testReadsLocalSecretFileWhenEnvMissing(): void
    {
        file_put_contents($this->secretsDir . '/API_TOKEN', 'filetoken');
        $val = secret('API_TOKEN');
        $this->assertSame('filetoken', $val);
    }

    public function testThrowsWhenRequiredAndMissing(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Missing required secret: MISSING_ONE');
        secret('MISSING_ONE', true);
    }

    public function testDefaultReturnedWhenOptionalAndMissing(): void
    {
        $val = secret('LOG_LEVEL', false, 'info');
        $this->assertSame('info', $val);
    }
}
