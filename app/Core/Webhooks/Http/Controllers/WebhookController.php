<?php

namespace App\Core\Webhooks\Http\Controllers;

use App\Core\Webhooks\Models\Webhook;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function index()
    {
        return Webhook::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event' => 'required',
            'url' => 'required|url',
            'secret' => 'nullable',
        ]);

        return Webhook::query()->create($data);
    }

    public function update(Request $request, Webhook $webhook)
    {
        $webhook->update($request->all());

        return response()->json($webhook->fresh());
    }

    public function destroy(Webhook $webhook)
    {
        $webhook->delete();

        return response()->json([
            'message' => 'Webhook deleted',
        ]);
    }
}
