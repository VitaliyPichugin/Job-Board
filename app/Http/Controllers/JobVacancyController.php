<?php

namespace App\Http\Controllers;

use App\Repositories\JobVacancyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class JobVacancyController extends Controller
{
    /**
     * @param JobVacancyRepository $jobVacancyRepository
     */
    public function __construct(protected JobVacancyRepository $jobVacancyRepository) {}

    /**
     * @return View
     */
    public function index(): View
    {
        return view('page.job_vacancy');
    }

    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse
    {
        try {
            return $this->jobVacancyRepository->getAll();
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate(['title' => 'required', 'description' => 'required']);
            return $this->jobVacancyRepository->createJobVacancy($request);
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function sendJobVacancyResponse(Request $request): JsonResponse
    {
        try {
            return $this->jobVacancyRepository->sendJobVacancyResponse($request);
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|int
     */
    public function like(Request $request): JsonResponse|int
    {
        try {
            return $this->jobVacancyRepository->like($request);
        } catch (\Exception $e) {
            return $this->jobVacancyRepository->failureResponse($e);
        }
    }
}
