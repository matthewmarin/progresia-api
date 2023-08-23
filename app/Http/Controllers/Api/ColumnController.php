<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ColumnStoreRequest;
use App\Http\Requests\ColumnUpdateRequest;
use App\Http\Resources\ColumnResource;
use App\Models\Column;
use App\Models\Subtask;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ColumnResource::collection(Column::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ColumnStoreRequest $request)
    {
        return ColumnResource::make(Column::create([
            'name' => $request->name,
            'board_id' => $request->boardId
        ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Column $column)
    {
        return ColumnResource::make($column);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ColumnUpdateRequest $request, Column $column)
    {
        if (isset($request->name)) {
            $column->name = $request->name;
        }

        if (isset($request->boardId)) {
            $column->board_id = $request->boardId;
        }

        $column->save();

        return ColumnResource::make($column);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Column $column)
    {
        try {
            $tasks = Task::where('column_id', $column->id)->get();

            foreach ($tasks as $task) {
                $tasks = Task::where('column_id', $column->id)->get();
                Subtask::where('task_id', $task->id)->delete();
                $task->delete();
            }

            $column->delete();


            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted',
            ]);
        } catch (Exception $err) {

            return response()->json([
                'success' => false,
                'message' => 'Cannot delete task',
                'error' => $err
            ]);
        }
    }
}
