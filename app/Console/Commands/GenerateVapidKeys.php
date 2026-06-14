<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateVapidKeys extends Command
{
    protected $signature   = 'vapid:generate';
    protected $description = 'Generate VAPID key pair for Web Push notifications';

    public function handle(): int
    {
        // On Windows openssl needs OPENSSL_CONF pointed at a valid cnf file
        if (PHP_OS_FAMILY === 'Windows' && !getenv('OPENSSL_CONF')) {
            $phpDir = dirname(PHP_BINARY);
            $candidates = [
                $phpDir . '/extras/ssl/openssl.cnf',
                $phpDir . '/../ssl/openssl.cnf',
                'C:/Program Files/OpenSSL-Win64/bin/openssl.cfg',
            ];
            foreach ($candidates as $cnf) {
                if (file_exists($cnf)) {
                    putenv('OPENSSL_CONF=' . $cnf);
                    break;
                }
            }
        }

        // Generate EC P-256 key pair
        $key = openssl_pkey_new([
            'curve_name'       => 'prime256v1',
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ]);

        if (!$key) {
            $this->error('openssl_pkey_new failed — check PHP openssl extension supports EC keys.');
            return 1;
        }

        $details = openssl_pkey_get_details($key);

        if (!isset($details['ec']['x'], $details['ec']['y'])) {
            $this->error('Could not extract EC coordinates from key.');
            return 1;
        }

        // Public key: uncompressed point (04 || x || y), 65 bytes, base64url
        $uncompressed = "\x04" . $details['ec']['x'] . $details['ec']['y'];
        $publicKey    = rtrim(strtr(base64_encode($uncompressed), '+/', '-_'), '=');

        // Private key: export as PEM, then base64 for .env storage
        openssl_pkey_export($key, $privateKeyPem);
        $privateKeyB64 = base64_encode($privateKeyPem);

        $this->updateEnv('VAPID_PUBLIC_KEY',  $publicKey);
        $this->updateEnv('VAPID_PRIVATE_KEY', $privateKeyB64);

        if (!env('VAPID_SUBJECT')) {
            $this->updateEnv('VAPID_SUBJECT', 'mailto:admin@' . parse_url(config('app.url'), PHP_URL_HOST));
        }

        $this->info('✅ VAPID keys generated and saved to .env');
        $this->line('Public key (paste in admin JS): ' . $publicKey);

        return 0;
    }

    private function updateEnv(string $key, string $value): void
    {
        $path    = base_path('.env');
        $content = file_get_contents($path);
        $escaped = preg_quote($key, '/');

        if (preg_match("/^{$escaped}=.*/m", $content)) {
            $content = preg_replace("/^{$escaped}=.*/m", $key . '=' . $value, $content);
        } else {
            $content .= PHP_EOL . $key . '=' . $value;
        }

        file_put_contents($path, $content);
    }
}
