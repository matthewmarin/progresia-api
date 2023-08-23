<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardStoreRequest;
use App\Http\Requests\BoardUpdateRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use App\Models\Column;
use App\Models\Subtask;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Board::query();
        if (isset($request->userId)) {
            $query->where('user_id', $request->userId);
        }
        return BoardResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BoardStoreRequest $request)
    {
        return BoardResource::make(
            Board::create([
                'name' => $request->name,
                'user_id' => $request->userId
            ])
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        return BoardResource::make($board->loadMissing('columns'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BoardUpdateRequest $request, Board $board)
    {
        if (isset($request->name)) {
            $board->name = $request->name;
        }

        if (isset($request->userId)) {
            $board->user_id = $request->userId;
        }

        $board->save();

        return BoardResource::make($board);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        try {
            $columns = Column::where('board_id', $board->id)->get();

            foreach ($columns as $column) {
                $tasks = Task::where('column_id', $column->id)->get();

                foreach ($tasks as $task) {
                    Subtask::where('task_id', $task->id)->delete();
                    $task->delete();
                }

                $column->delete();
            }

            $board->delete();


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
