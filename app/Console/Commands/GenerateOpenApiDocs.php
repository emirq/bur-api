<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class GenerateOpenApiDocs extends Command
{
    protected $signature = 'generate:openapi-docs';
    protected $description = 'Generate OpenAPI documentation';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        // Include the directory where the schemas are defined
        $openapi = Generator::scan([app_path('Http/Controllers'), app_path('OpenApi')]);
        file_put_contents(base_path('openapi.yml'), $openapi->toYaml());
        $this->info('OpenAPI documentation generated successfully.');
    }
}
