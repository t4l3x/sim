<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Services\PredictionService;
use App\Http\Helpers\ApiHelpers;
use App\Http\Requests\WeekRequest;
use App\Models\Leagues;
use Illuminate\Http\JsonResponse;

class PredictionController extends Controller
{
    public function __construct(
        protected PredictionService $predictionService,
    )
    {
    }

    public function show(Leagues $league, WeekRequest $request): JsonResponse
    {
        $week = $request->validated()['week'];

        $predictions = $this->predictionService->getPredictions($league->id,$week);

        return ApiHelpers::successResponse('OK', ['predictions' => $predictions]);

    }
}
