<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use OpenApi\Generator;

class GenerateOpenApiDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:openapi-docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate OpenAPI documentation';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        // Include the directory where the schemas are defined
        $openapi = Generator::scan([app_path('Http/Controllers'), app_path('OpenApi')]);

        if ($openapi) {
            file_put_contents(base_path('openapi.yml'), $openapi->toYaml());
            $this->info('OpenAPI documentation generated successfully.');
        } else {
            $this->error('Failed to generate OpenAPI documentation: No valid annotations found.');
        }
    }
}
