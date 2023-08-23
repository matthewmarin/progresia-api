<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Subtask;
use App\Models\Task;
use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();
        if (isset($request->columnId)) {
            $query->where('column_id', $request->columnId);
        }
        return TaskResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        return TaskResource::make(Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'column_id' => $request->columnId,
            'is_completed' => $request->isCompleted
        ]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return TaskResource::make($task->loadMissing('subtasks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {

        if (isset($request->title)) {
            $task->title = $request->title;
        }

        if (isset($request->description)) {
            $task->description = $request->description;
        }

        if (isset($request->status)) {
            $task->status = $request->status;
        }

        if (isset($request->columnId)) {
            $task->column_id = $request->columnId;
        }

        if (isset($request->subtaskId)) {
            $task->subtask_id = $request->subtaskId;
        }
        $task->save();

        return TaskResource::make($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {

        try {
            Subtask::where('task_id', $task->id)->delete();

            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted'
            ]);
        } catch (Exception $err) {

            return response()->json([
                'success' => false,
                'message' => 'Cannot delete task'
            ]);
        }
    }
}
