<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubtaskStoreRequest;
use App\Http\Requests\SubtaskUpdateRequest;
use App\Http\Resources\SubtaskResource;
use App\Models\Subtask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SubtaskResource::collection(Subtask::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubtaskStoreRequest $request)
    {
        return SubtaskResource::make(Subtask::create([
            'title' => $request->title,
            'task_id' => $request->taskId
        ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Subtask $subtask)
    {
        return SubtaskResource::make($subtask);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubtaskUpdateRequest $request, Subtask $subtask)
    {
        if (isset($request->title)) {
            $subtask->title = $request->title;
        }

        if (isset($request->taskId)) {
            $subtask->task_id = $request->taskId;
        }

        $subtask->save();

        return SubtaskResource::make($subtask);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtask $subtask)
    {
        //
    }
}
