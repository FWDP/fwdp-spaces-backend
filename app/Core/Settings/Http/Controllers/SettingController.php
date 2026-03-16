<?php

namespace App\Core\Settings\Http\Controllers;

use App\Core\Settings\Services\SettingsService;
use App\Http\Controllers\Controller;
use App\Core\Settings\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected SettingsService $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        return Setting::all();
    }

    public function show(string $key)
    {
        return response()->json([
           'key' => $key,
           'value' => $this->settingsService->get($key)
        ]);
    }

    public function update(Request $request, string $key)
    {
        $value = $request->json('value');

        $this->settingsService->set($key, $value);

        return response()->json([
            'message' => 'Setting updated'
        ]);
    }
}
