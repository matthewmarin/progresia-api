<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BoardStoreRequest;
use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BoardResource::collection(Board::paginate());
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
        return BoardResource::make($board);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
