<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CoinService
{
    private int $cost;

    private User $user;

    /**
     * @param User $user
     * @param string $type
     */
    public function __construct(User $user, string $type = '')
    {
        $this->cost = match ($type) {
            'SEND_RESPONSE' => config('global.send_response'),
            'POST_JOB' => config('global.post_job'),
        };
        $this->user = $user;
    }

    /**
     * @return void
     */
    public function updateUserCoins(): void
    {
        $coins = $this->user->coin - $this->cost;
        if ($coins >= 0) {
            $this->user->coin = $coins;
            $this->user->save();
        }
    }

    /**
     * @return void
     */
    static public function addCoins(): void
    {
        $users = User::all();
        $new_coins = config('global.add_coins');
        foreach ($users as $user) {
            $user_coins = $user->coin;
            $user_coins = $user->coin <= 5 ? $user_coins : 5;
            $user->coin = $user_coins + $new_coins;//accumulated up to 5 coins
            if($user->save()) {
                Mail::raw("$new_coins coins credited. On your account $user->coin coins", function ($mail) use ($user) {
                    $mail->to($user['email'])
                        ->subject('Daily Refill!');
                });
            }
        }
    }

    /**
     * @return bool
     */
    public function checkUserCoins(): bool
    {
        return ($this->user->coin - $this->cost) >= 0 ? true : false;
    }
}
