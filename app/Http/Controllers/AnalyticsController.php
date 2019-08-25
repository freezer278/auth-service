<?php

namespace App\Http\Controllers;

use App\Utils\AnalyticsStorage\AnalyticsStorage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AnalyticsController extends Controller
{
    /**
     * @var AnalyticsStorage
     */
    private $analyticsStorage;

    /**
     * AnalyticsController constructor.
     * @param AnalyticsStorage $analyticsStorage
     */
    public function __construct(AnalyticsStorage $analyticsStorage)
    {
        $this->analyticsStorage = $analyticsStorage;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function saveRecord(Request $request)
    {
        $this->validate($request, [
            'source_label' => 'required|string',
            'id_user' => 'nullable|integer',
        ]);

        $item = $this->analyticsStorage->create($request->only('source_label', 'id_user'));

        return response()->json($item);
    }
}
