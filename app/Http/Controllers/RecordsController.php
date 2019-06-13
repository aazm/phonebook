<?php

namespace App\Http\Controllers;

use App\Helpers\EmptyDataSet;
use App\Http\Requests\ChangeRecordRequest;
use App\Record;
use App\Services\RecordsServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dataset = resolve(RecordsServiceInterface::class)
            ->read(
                $request->get('page', 1),
                $request->get('size', config('phonebook.page_max_size')),
                $request->get('name')
            );

        if($dataset instanceof EmptyDataSet) {
            return response()->json([], 404);
        }

        return response()->json(['items' => $dataset->getItems(), 'total' => $dataset->getTotal()]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChangeRecordRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChangeRecordRequest $request)
    {
        $record = resolve(RecordsServiceInterface::class)
            ->create($request->all());

        return response()->json(['success' => true, 'record' => $record->toArray()]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $record = resolve(RecordsServiceInterface::class)->show($id);
        if(!$record) return response()->json(['success' => false], 404);

        return response()->json(['success' => true, 'record' => $record]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param ChangeRecordRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ChangeRecordRequest $request, $id)
    {
        try {
            return response()->json(['success' => true,
                'record' => resolve(RecordsServiceInterface::class)->update($id, $request->toArray())
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'record not found']);

        } catch (QueryException $e) {
            return response()->json(['success' => false, 'message' => 'unable to perform request']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json([
            'success' => resolve(RecordsServiceInterface::class)->delete($id)
        ]);
    }
}
