<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Todo::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,completed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $todo = Todo::create($request->all());
        return response()->json($todo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo): \Illuminate\Http\JsonResponse
    {
        return response()->json($todo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|string|in:pending,completed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $todo->update($request->all());

        return response()->json($todo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo): \Illuminate\Http\JsonResponse
    {
        $todo->delete();

        return response()->json(null, 204);
    }
}
