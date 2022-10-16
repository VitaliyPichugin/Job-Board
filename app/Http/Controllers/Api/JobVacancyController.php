<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobVacancyRequest;
use App\Http\Resources\JobVacancyResource;
use App\Http\Resources\LikedResource;
use App\Models\JobVacancy;
use App\Repositories\JobVacancyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobVacancyController extends Controller
{
    /**
     * @param JobVacancyRepository $jobVacancyRepository
     */
    public function __construct(protected JobVacancyRepository $jobVacancyRepository)
    {
        $this->middleware('auth:api', ['except' => ['getListJobVacancies', 'show', 'index']]);
    }

    /**
     * Get all vacancies
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return JobVacancyResource::collection(JobVacancy::all());
    }

    /**
     *  Fetch a single job vacancy.
     *
     * @param $id
     * @return AnonymousResourceCollection
     */
    public function show($id): AnonymousResourceCollection
    {
        return JobVacancyResource::collection(JobVacancy::whereId($id)->get());
    }

    /**
     * @param JobVacancyRequest $request
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function getListJobVacancies(JobVacancyRequest $request): JsonResponse|AnonymousResourceCollection
    {
        try {
            return JobVacancyResource::collection($this->jobVacancyRepository->getListJobVacancies($request));
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }

    /**
     * Create job vacancy
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createJobVacancy(Request $request) : JsonResponse
    {
        try {
            $request->validate([
                                   'title' => 'required',
                                   'description' => 'required',
                               ]);

            return $this->jobVacancyRepository->createJobVacancy($request);
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }

    /**
     * Update job vacancy
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function updateJobVacancy(Request $request, $id): JsonResponse
    {
        try {
            $request->validate([
                                   'title' => 'required',
                                   'description' => 'required',
                               ]);

            return $this->jobVacancyRepository->updateJobVacancy($request, $id);
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteJobVacancy($id): JsonResponse
    {
        try {
            return $this->jobVacancyRepository->deleteJobVacancy($id);
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendJobVacancyResponse(Request $request) : JsonResponse
    {
        try {
            return $this->jobVacancyRepository->sendJobVacancyResponse($request);
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteResponse($id): JsonResponse
    {
        try {
            return $this->jobVacancyRepository->deleteResponse($id);
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }

    /**
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function getLiked(): JsonResponse|AnonymousResourceCollection
    {
        try {
            return LikedResource::collection($this->jobVacancyRepository->getLiked());
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }
}
