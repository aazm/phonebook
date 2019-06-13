<?php

namespace App\Http\Controllers;

use App\Services\MetaServiceInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;

class MetaController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        try {
            return response()->json(resolve(MetaServiceInterface::class)->get());
        } catch (FileNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'export file is not ready yet']);
        }
    }
}
