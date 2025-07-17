<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use App\Http\Resources\ExchangeRateResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExchangeRateController extends Controller
{
    /**
     * List Exchange Rates.
     *
     * Retrieve a paginated list of exchange rates with optional filtering by currency and date.
     *
     * @query exchangeRate int required The ID of the exchange rate to retrieve.
     */
    #[QueryParameter('currency', description: 'Filter exchange rates by currency code.', type: 'string', example: 'USD')]
    #[QueryParameter('date', description: 'Filter exchange rates by date (YYYY-MM-DD).', type: 'string', example: '2025-07-15')]
    #[QueryParameter('per_page', description: 'Number of results per page.', type: 'int', default: 15, example: '10')]
    #[QueryParameter('page', description: 'Page number to retrieve.', type: 'int', default: 1, example: 2)]
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ExchangeRate::with('day');

        if ($request->filled('currency')) {
            $query->where('currency', $request->input('currency'));
        }

        if ($request->filled('date')) {
            $query->whereHas('day', function ($q) use ($request) {
                $q->where('date', $request->input('date'));
            });
        }

        $paginated = $query->paginate($request->input('per_page', 15));
        return ExchangeRateResource::collection($paginated);
    }

    /**
     * Display the specified exchange rate.
     *
     * @urlParam exchangeRate int required The ID of the exchange rate to retrieve.
     */
    public function show(ExchangeRate $exchangeRate): ExchangeRateResource
    {
        return new ExchangeRateResource($exchangeRate);
    }
}
