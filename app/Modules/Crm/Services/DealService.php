<?php

namespace App\Modules\Crm\Services;

use App\Modules\Crm\Models\Deal;
use Illuminate\Database\Eloquent\Collection;

class DealService
{
    public function listDeals(): Collection
    {
        return Deal::query()->with(['contact'])->orderByDesc('id')->get();
    }

    public function getDeal(Deal $deal): Deal
    {
        return $deal->load(['contact', 'activities']);
    }

    public function createDeal(array $data): Deal
    {
        return Deal::query()->create($data);
    }

    public function updateDeal(Deal $deal, array $data): Deal
    {
        $deal->update($data);

        return $deal->fresh(['contact']);
    }

    public function updateStage(Deal $deal, string $stage): Deal
    {
        $deal->update(['stage' => $stage]);

        return $deal->fresh();
    }

    public function deleteDeal(Deal $deal): void
    {
        $deal->delete();
    }
}
