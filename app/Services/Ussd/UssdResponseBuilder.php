<?php

namespace App\Services\Ussd;

class UssdResponseBuilder
{
    public function __construct(
        protected int $maxLength = 182
    ) {
        $this->maxLength = (int) config('ussd.max_response_length', 182);
    }

    public function continuation(string $body): string
    {
        return $this->wrap('CON', $body);
    }

    public function end(string $body): string
    {
        return $this->wrap('END', $body);
    }

    protected function wrap(string $prefix, string $body): string
    {
        // Collapse horizontal spaces only; keep newlines for multi-line USSD menus.
        // preg_replace can return null on PREG errors — never pass null to trim() (TypeError on PHP 8+).
        $normalized = preg_replace('/ +/u', ' ', str_replace(["\r\n", "\r"], "\n", $body));
        $body = trim(is_string($normalized) ? $normalized : $body);
        $out = $prefix.' '.$body;
        if (strlen($out) <= $this->maxLength) {
            return $out;
        }

        $budget = $this->maxLength - strlen($prefix) - 1;
        if ($budget < 8) {
            return $prefix.' Error.';
        }

        return $prefix.' '.substr($body, 0, $budget - 3).'...';
    }
}
