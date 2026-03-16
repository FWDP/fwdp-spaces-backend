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
        return $webhook->update($request->all());
    }

    public function destroy(Webhook $webhook)
    {
        $webhook->delete();

        return response()->json([
            'message' => 'Webhook deleted'
        ]);
    }
}
