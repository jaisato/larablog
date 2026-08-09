<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $this->ensureEncryptionKey($app);

        return $app;
    }

    /**
     * Without an APP_KEY the encrypter cannot be resolved and every feature
     * test dies with MissingAppKeyException, so a fresh clone could not run the
     * suite at all.
     *
     * The key is generated per run rather than committed: a literal in
     * phpunit.xml is indistinguishable from a real leaked key to a secret
     * scanner, and pinning one buys nothing here - no test depends on
     * ciphertext surviving between runs.
     */
    private function ensureEncryptionKey(Application $app): void
    {
        if (! empty($app['config']->get('app.key'))) {
            return;
        }

        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }
}
