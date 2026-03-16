<?php

namespace App\Modules\Marketplace\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Marketplace\Services\MarketplaceService;
use Illuminate\Http\JsonResponse;

class MarketplaceController extends Controller
{
    public function __construct(protected MarketplaceService $service) {}

    public function index(): JsonResponse
    {
        return response()->json($this->service->listModules());
    }

    public function install(string $module): JsonResponse
    {
        return response()->json($this->service->installModule($module), 201);
    }

    public function uninstall(string $module): JsonResponse
    {
        return response()->json($this->service->uninstallModule($module));
    }

    public function toggle(string $module): JsonResponse
    {
        return response()->json($this->service->toggleModule($module));
    }
}
