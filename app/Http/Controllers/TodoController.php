<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Get todos list (without softly deleted)
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        return view("todolist")->with(
            "todos",
            Todo::withoutTrashed()->orderByDesc("id")->get()
        );
    }


    /**
     * Restores specified "to do" from softRemoved
     *
     * @param int $id
     */
    public function restore($id)
    {
        return response()->json(Todo::withTrashed()->where("id", $id)->restore(), 200);
    }


    /**
     * Get softly removed(completed) todos
     *
     * @return JsonResponse
     */
    public function completed()
    {
        return response()->json(Todo::onlyTrashed()->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $todo = new Todo;
        $todo->text = $request->text;
        $todo->save();

        return response()->json($todo);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $todoId
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $todoId, Request $request)
    {
        return response()->json(Todo::where("id", $todoId)->update(["text" => $request->text]));
    }


    public function complete(int $todoId)
    {
        return response()->json(Todo::where("id", $todoId)->delete());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $todoId
     * @return JsonResponse
     */
    public function destroy(int $todoId)
    {
        return response()->json(Todo::where("id", $todoId)->forceDelete());
    }
}
