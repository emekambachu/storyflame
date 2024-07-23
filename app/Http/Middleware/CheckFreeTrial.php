<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\FreeTrialService;

class CheckFreeTrial
{
    protected $freeTrialService;

    public function __construct(FreeTrialService $freeTrialService)
    {
        $this->freeTrialService = $freeTrialService;
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->subscription('default')->paddle_id === config('free_trial.paddle_id')) {
            if (!$this->freeTrialService->canInteract($user)) {
                return response()->json(['error' => 'Free trial limit reached'], 403);
            }
        }

        return $next($request);
    }
}
