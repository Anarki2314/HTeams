<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatusResource;
use App\Models\EventStatus;
use Illuminate\Http\Request;

class StatusController extends Controller
{

    public function index()
    {
        $statuses = EventStatus::where('title', '!=', 'На проверке')
            ->where('title', '!=', 'Отменено')
            ->get();

        return response()->json([
            'data' => StatusResource::collection($statuses)
        ]);
    }
}
