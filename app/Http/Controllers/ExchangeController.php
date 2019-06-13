<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Services\ExchangeServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ExchangeController extends Controller
{

    /**
     * @param UploadedFile $file
     */
    public function import(ImportRequest $request)
    {

        try {
            /** @var ExchangeServiceInterface $service */
            $service = resolve(ExchangeServiceInterface::class);
            $service->import($request->file('import'));

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false]);
        }
    }
}
