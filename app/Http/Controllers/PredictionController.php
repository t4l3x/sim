<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\PredictionService;
use App\Http\Helpers\ApiHelpers;
use App\Http\Requests\WeekRequest;
use Illuminate\Http\JsonResponse;

class PredictionController extends Controller
{
    public function __construct(
        protected PredictionService $predictionService,
    )
    {
    }

    public function show(WeekRequest $request): JsonResponse
    {
        $week = $request->validated()['week'];

        $predictions = $this->predictionService->getPredictions($week);

        return ApiHelpers::successResponse('OK', ['predictions' => $predictions]);

    }
}
