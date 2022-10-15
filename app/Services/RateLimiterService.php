<?php

namespace App\Services;

use Carbon\CarbonInterval;
use Illuminate\Cache\RateLimiter as CacheLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter as BaseRateLimiter;

class RateLimiterService
{
    /**
     * @param int $user
     * @param string $key
     * @param int $maxAttempts
     * @param int $decaySeconds
     */
    public function __construct(
        protected int $user = 0,
        protected string $key = '',
        protected int $maxAttempts = 0,
        protected int $decaySeconds = 0,
    ) {}

    /**
     * @param \Closure $callback
     * @return void
     * @throws \Exception
     */
    public function throttle(\Closure $callback): void
    {
        $key = "{$this->key}:{$this->user}";
        $executed = BaseRateLimiter::attempt(
            $key,
            $this->maxAttempts,
            $callback,
            $this->decaySeconds,
        );
        if (!$executed) {
            $value = $this->limiter()->availableIn($key);
            $dt = Carbon::now();
            $days = $dt->diffInDays($dt->copy()->addSeconds($value));
            $hours = $dt->diffInHours($dt->copy()->addSeconds($value)->subDays($days));
            $minutes = $dt->diffInMinutes($dt->copy()->addSeconds($value)->subDays($days)->subHours($hours));
            $tryAgain = CarbonInterval::days($days)->hours($hours)->minutes($minutes)->forHumans();
            throw new ThrottleRequestsException("Too Many Attempts. Try again in $tryAgain");
        }
    }

    /**
     *
     * @return CacheLimiter
     */
    protected function limiter(): CacheLimiter
    {
        return app(CacheLimiter::class);
    }
}
