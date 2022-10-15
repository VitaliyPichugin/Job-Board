<?php
namespace App\Repositories;

use App\Jobs\SendJobVacancyResponse;
use App\Models\JobVacancy;
use App\Models\JobVacancyResponse;
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
            $obj = new RateLimiter(
                (int)$user,
                'send-job',
                200,//config('global.limit_send_job'),
                config('global.period_send_job')
            );
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

    /**
     * @param $request
     * @return JsonResponse
     */
    public function sendJobVacancyResponse($request): JsonResponse
    {
        $job_id = $request->job_id;
        if (!JobVacancy::author($job_id)->first()) {
            $id = auth()->id();
            $user = User::find($id);
            $coinService = new CoinService($user, 'SEND_RESPONSE');
            if ($coinService->checkUserCoins()) {
                $responses = $user->responses();
                $author = User::find($request->author_id)->proposals();
                $delay = $this->getDelay($author);
                $response = $responses->firstOrCreate([
                                                          'job_id' => $job_id,
                                                          'user_id' => $id,
                                                      ]);
                if (!$response->wasRecentlyCreated) {
                    return response()->json("Proposal has already been sent", 201);
                } else {
                    $coinService->updateUserCoins();
                    $data = $this->getJobVacancyData($request->job_id);
                    $data_email = [
                        'job_title' => $data[0]['job']['title'],
                        'user' => $user->name,
                        'email' => $data[0]['job']['user']['email'],
                        'responses' => JobVacancyResponse::where('job_id', $request->job_id)->count(),
                        'date' => $response['created_at']->toString()
                    ];
                    SendJobVacancyResponse::dispatch($response, $data_email)->delay(now()->addMilliseconds($delay));
                    $human = $this->convertMilliseconds($delay);
                    $response = "Proposal has been sent";
                    if ($human) {
                        $response .= " (Author will be notified in $human)";
                    }
                    return response()->json($response);
                }
            }
            return response()->json("Sorry not enough coins for send response", 201);
        }
        return response()->json("You are can't send proposal on your job", 201);
    }

    /**
     * @param $request
     *
     */
    public function like($request)
    {
    }

    /**
     * @param $job
     * @return mixed
     */
    private function getJobVacancyData($job): mixed
    {
        return JobVacancyResponse::where('job_id', $job)
            ->with('job.user')
            ->get();
    }

    /**
     * @param $proposals
     * @return float|int
     */
    private function getDelay($proposals): float|int
    {
        $order_proposal = $proposals->orderBy('updated_at', 'asc')->get();
        $order_proposal_cnt = $order_proposal->count();
        if ($order_proposal_cnt) {
            $dates = [];
            $delay_config = config('global.delay_send_response');
            $i = 0;
            foreach ($order_proposal as $proposal) {
                $diff = 0;
                if ($i < 1 || $order_proposal_cnt === 1) {
                    $diff = now()->diffInMilliseconds($proposal['updated_at']);
                }
                $delay = $delay_config - $diff;
                if ($delay > 0) {
                    $dates[] = $delay;
                }
                $i++;
            }
            return array_sum($dates);
        }
        return 0;
    }

    /**
     * @param $value
     * @return int|string
     */
    private function convertMilliseconds($value): int|string
    {
        if ($value) {
            return date("H:i:s", $value / 1000);
        }
        return 0;
    }

    /**
     * @param \Exception $e
     * @return JsonResponse
     */
    public function failureResponse(\Exception $e): JsonResponse
    {
        return response()->json("Error in {$e->getLine()} line: {$e->getMessage()}");
    }

}
