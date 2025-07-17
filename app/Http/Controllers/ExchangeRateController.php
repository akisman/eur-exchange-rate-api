<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExchangeRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
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

        $paginated->getCollection()->transform(function ($rate) {
            return [
                'currency' => $rate->currency,
                'rate' => number_format($rate->rate, 6),
                'date' => $rate->day->date,
            ];
        });

        return response()->json($paginated);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExchangeRate $exchangeRate): JsonResponse
    {
        return response()->json([
            'data' => [
                'id' => $exchangeRate->id,
                'currency' => $exchangeRate->currency,
                'rate' => number_format($exchangeRate->rate, 6),
                'date' => optional($exchangeRate->day)->date,
            ]
        ]);
    }
}
