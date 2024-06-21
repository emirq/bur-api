<?php

namespace App\Http\Controllers;

use App\Exceptions\TodoValidationException;
use App\Helpers\ApiResponse;
use App\Services\TodoService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Illuminate\Http\Request;

#[OA\Info(
    version: "1.0.0",
    description: "This is a Todo App Documentation",
    title: "Todo API"
)]
class TodoController extends Controller
{
    public function __construct(private readonly TodoService $todoService)
    {}

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
    public function index(): JsonResponse
    {
        $todos = $this->todoService->all();

        return ApiResponse::success($todos);
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
    public function store(Request $request): JsonResponse
    {
        try {
            $todo = $this->todoService->store($request);

            return ApiResponse::success(
                $todo,
                'Todo successfully created.',
                JsonResponse::HTTP_CREATED
            );
        } catch (TodoValidationException $e) {
            return ApiResponse::validationError($e->getMessage());
        }
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
    public function show(int $id): JsonResponse
    {
        try {
            $todo = $this->todoService->show($id);

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
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $todo = $this->todoService->update($request, $id);

            return ApiResponse::success($todo, 'Todo successfully updated.');
        } catch(TodoValidationException $e) {
            return ApiResponse::validationError($e->getMessage());
        } catch (ModelNotFoundException) {
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
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->todoService->destroy($id);

            return ApiResponse::success([], '', JsonResponse::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException) {
            return ApiResponse::error('Todo not found', JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
