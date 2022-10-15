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
    public function __construct(protected  JobVacancyRepository $jobVacancyRepository)
    {

    }

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
}
