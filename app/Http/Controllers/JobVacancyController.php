<?php

namespace App\Http\Controllers;

use App\Repositories\JobVacancyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    /**
     * @param JobVacancyRepository $jobVacancyRepository
     */
    public function __construct(protected  JobVacancyRepository $jobVacancyRepository) {}

    public function index()
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
            return response()->json($e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|null
     */
    public function store(Request $request)
    {
        try {
            $request->validate(['title' => 'required', 'description' => 'required']);
            return $this->jobVacancyRepository->createJobVacancy($request);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
