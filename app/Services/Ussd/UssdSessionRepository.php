<?php

namespace App\Services\Ussd;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;

class UssdSessionRepository
{
    /** @var Repository|null */
    protected static ?Repository $store = null;

    public function get(string $sessionId): array
    {
        return $this->store()->get($this->key($sessionId), []);
    }

    public function put(string $sessionId, array $data): void
    {
        $ttl = (int) config('ussd.session_ttl_seconds', 300);
        $this->store()->put($this->key($sessionId), $data, now()->addSeconds(max(60, $ttl)));
    }

    public function forget(string $sessionId): void
    {
        $this->store()->forget($this->key($sessionId));
    }

    protected function store(): Repository
    {
        if (self::$store !== null) {
            return self::$store;
        }

        $name = (string) config('ussd.cache_store', 'file');
        try {
            self::$store = Cache::store($name);
        } catch (\Throwable) {
            self::$store = Cache::store('file');
        }

        return self::$store;
    }

    protected function key(string $sessionId): string
    {
        return 'ussd:session:'.hash('sha256', $sessionId);
    }
}
