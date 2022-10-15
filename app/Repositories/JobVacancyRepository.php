<?php
namespace App\Repositories;

use App\Models\JobVacancy;
use App\Models\UserLike;
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
        $data = JobVacancy::with(['user', 'responses'])
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
}
