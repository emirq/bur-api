<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

#[OA\Info(
    version: "1.0.0",
    description: "This is a Todo App Documentation",
    title: "Todo API"
)]
class TodoController extends Controller
{
    #[OA\Get(
        path: '/api/todos',
        description: 'Retrieve a list of all todos',
        summary: 'Get list of todos',
        tags: ['Todos'],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'List of todos',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Todo')
                )
            )
        ]
    )]
    public function index(): \Illuminate\Http\JsonResponse
    {
        return ApiResponse::success(Todo::all());
    }

    #[OA\Post(
        path: '/api/todos',
        description: 'Create a new todo item',
        summary: 'Create a new todo',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'status'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'New Todo'),
                    new OA\Property(property: 'description', type: 'string', example: 'This is a new todo item', nullable: true),
                    new OA\Property(property: 'status', type: 'string', enum: ['pending', 'completed'], example: 'pending')
                ]
            )
        ),
        tags: ['Todos'],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_CREATED,
                description: 'Todo created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Todo')
            ),
            new OA\Response(response: JsonResponse::HTTP_UNPROCESSABLE_ENTITY, description: 'Validation error')
        ]
    )]
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,completed',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        $todo = Todo::create($request->all());

        return ApiResponse::success($todo, '', JsonResponse::HTTP_CREATED);
    }

    #[OA\Get(
        path: '/api/todos/{id}',
        description: 'Retrieve a specific todo item by ID',
        summary: 'Get a specific todo',
        tags: ['Todos'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'The specified todo',
                content: new OA\JsonContent(ref: '#/components/schemas/Todo')
            ),
            new OA\Response(response: JsonResponse::HTTP_NOT_FOUND, description: 'Todo not found')
        ]
    )]
    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $todo = Todo::findOrFail($id);

            return ApiResponse::success($todo);
        } catch (ModelNotFoundException) {
            return ApiResponse::error('Todo not found', JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[OA\Put(
        path: '/api/todos/{id}',
        description: 'Update a specific todo item by ID',
        summary: 'Update a specific todo',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Updated Todo'),
                    new OA\Property(property: 'description', type: 'string', example: 'This is an updated todo item', nullable: true),
                    new OA\Property(property: 'status', type: 'string', enum: ['pending', 'completed'], example: 'completed')
                ]
            )
        ),
        tags: ['Todos'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: JsonResponse::HTTP_OK,
                description: 'Todo updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Todo')
            ),
            new OA\Response(response: JsonResponse::HTTP_UNPROCESSABLE_ENTITY, description: 'Validation error'),
            new OA\Response(response: JsonResponse::HTTP_NOT_FOUND, description: 'Todo not found')
        ]
    )]
    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|string|in:pending,completed',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        try {
            $todo = Todo::findOrFail($id);

            $todo->update($request->all());

            return ApiResponse::success($todo);
        }  catch (ModelNotFoundException) {
            return ApiResponse::error('Todo not found', JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[OA\Delete(
        path: '/api/todos/{id}',
        description: 'Delete a specific todo item by ID',
        summary: 'Delete a specific todo',
        tags: ['Todos'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: JsonResponse::HTTP_NO_CONTENT, description: 'Todo deleted successfully'),
            new OA\Response(response: JsonResponse::HTTP_NOT_FOUND, description: 'Todo not found')
        ]
    )]
    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $todo = Todo::findOrFail($id);
            $todo->delete();

            return ApiResponse::success([], '', JsonResponse::HTTP_NO_CONTENT);

        } catch (ModelNotFoundException) {
            return ApiResponse::error('Todo not found', JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
