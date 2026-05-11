<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Prints webhook URLs for operations (Africa's Talking USSD + M-Pesa Daraja STK).
 *
 * Africa's Talking does not read Laravel config — you copy the printed USSD URL
 * into the AT dashboard. Values come from .env via config/ussd.php and config/mpesa.php.
 *
 * @see config/ussd.php Env precedence: USSD_CALLBACK_URL, USSD_PUBLIC_APP_URL, APP_URL.
 * @see routes/ussd.php Route definitions and troubleshooting notes.
 */
class ShowUssdCallbackUrlsCommand extends Command
{
    protected $signature = 'ussd:callbacks';

    protected $description = 'Print Africa\'s Talking USSD webhook URLs and M-Pesa STK callback (set APP_URL or USSD_PUBLIC_APP_URL on the server)';

    public function handle(): int
    {
        $base = rtrim((string) config('ussd.public_app_url'), '/');
        $primary = (string) config('ussd.africastalking_webhook_url');
        $alt = $base.'/ussd/africastalking';
        $stk = (string) config('mpesa.stk_callback_url', '');

        $this->line('Public base (USSD_PUBLIC_APP_URL or APP_URL): '.$base);
        $this->newLine();
        $this->info('Africa\'s Talking — paste ONE of these (recommended first):');
        $this->line('  '.$primary);
        $this->line('  '.$alt);
        $this->newLine();
        $this->comment('Register in Daraja (production):');
        if ($stk !== '') {
            $this->line('  '.$stk);
        } else {
            $this->warn('  MPESA_STK_CALLBACK_URL is not set. Example: '.$base.'/api/mpesa/stk-callback');
        }

        return self::SUCCESS;
    }
}
