<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\GovernorateResource;
use App\Http\Resources\CompoundResource;
use App\Http\Resources\UnitResource;
use App\Http\Resources\UnitTypeResource;
use App\Http\Resources\UnitListResource;
use App\Service\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function globalSearch(Request $request)
    {
        $query = $request->get('q');

        if (empty($query)) {
            return $this->error(__('api.search_query_required'), 400);
        }

        $results = $this->searchService->globalSearch($query);

        $data = [];
        if ($results['units']->isNotEmpty()) {
            $data['units'] = UnitListResource::collection($results['units']);
        }
        if ($results['governorates']->isNotEmpty()) {
            $data['governorates'] = $results['governorates']->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->{"name_" . app()->getLocale()} ?: $c->name_ar ?: $c->name_en,
            ]);
        }
        if ($results['compounds']->isNotEmpty()) {
            $data['compounds'] = $results['compounds']->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->{"name_" . app()->getLocale()} ?: $c->name_ar ?: $c->name_en,
            ]);
        }
        if ($results['unit_types']->isNotEmpty()) {
            $data['unit_types'] = $results['unit_types']->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->{"name_" . app()->getLocale()} ?: $t->name_ar ?: $t->name_en,
            ]);
        }

        if ($results['developers']->isNotEmpty()) {
            $data['developers'] = $results['developers']->map(fn($d) => [
                'id' => $d->id,
                'name' => $d->{"name_" . app()->getLocale()} ?: $d->name_ar ?: $d->name_en,
                'logo' => $d->logo ? Storage::disk('public')->url($d->logo) : '',
            ]);
        }

        if ($results['sellers']->isNotEmpty()) {
            $data['sellers'] = $results['sellers']->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'avatar' => $s->avatar ? Storage::disk('public')->url($s->avatar) : '',
            ]);
        }

        if (empty($data)) {
            return $this->error(__('api.not_found'), 200);
        }

        return $this->success($data);
    }
}
