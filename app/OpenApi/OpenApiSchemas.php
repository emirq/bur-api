<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Todo",
    properties: [
        'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
        'title' => new OA\Property(property: 'title', type: 'string', example: 'My Todo'),
        'description' => new OA\Property(property: 'description', type: 'string', nullable: true, example: 'This is a description'),
        'status' => new OA\Property(property: 'status', type: 'string', enum: ['pending', 'completed'], example: 'pending'),
        'created_at' => new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2022-01-01T00:00:00Z'),
        'updated_at' => new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2022-01-01T00:00:00Z')
    ],
    type: "object"
)]
class OpenApiSchemas
{
}
