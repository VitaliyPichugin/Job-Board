<?php
namespace App\Repositories;

use App\Models\JobVacancy;
use App\Models\User;
use App\Models\UserLike;
use App\Services\CoinService;
use App\Services\RateLimiterService as RateLimiter;
use Illuminate\Http\JsonResponse;

class JobVacancyRepository
{
    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        $userLikes = UserLike::with(['likeUser', 'likeJob'])
            ->whereId(auth()->id())
            ->get()
            ->map(function ($item) {
                $item->like_users = $item->likeUser->pluck('id')->toArray();
                $item->like_jobs = $item->likeJob->pluck('id')->toArray();
                return $item;
            });
        $data = JobVacancy::with(['tags', 'user', 'responses'])
            ->get()
            ->map(function ($item) {
                $item->diff_human = $item->created_at->diffForHumans();
                return $item;
            });

        return response()->json([
                                    'likes' => $userLikes->first(),
                                    'data' => $data,
                                ]);
    }

    /**
     * @param $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function createJobVacancy($request): JsonResponse
    {
        $user = auth()->id();
        $coinService = new CoinService(User::find($user), 'POST_JOB');
        if ($coinService->checkUserCoins()) {
            $obj = new RateLimiter((int)$user, 'send-job', 200, 3600 * 24);
            $obj->throttle(function () use ($request, $user, $coinService) {
                $job = new JobVacancy();
                $job->title = $request->title;
                $job->description = $request->description;
                $job->user_id = $user;
                $job->save();
                $job->tags()->attach(collect($request->tags)->pluck('id'));
                $coinService->updateUserCoins();
            });
            return response()->json("Job was posted");
        } else {
            return response()->json("Sorry not enough coins for send response", 201);
        }
    }


}
