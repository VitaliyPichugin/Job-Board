<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\JobVacancyRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
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
