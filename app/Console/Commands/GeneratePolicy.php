<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;


class GeneratePolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-model-policy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate policy classes for each model, excluding specified models.';

    /**
     * Models to exclude from policy generation.
     *
     * @var array
     */
    protected array $exemptedModels = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelPath = app_path('Models');

        $models = collect(File::files($modelPath))
            ->map(fn($file) => 'App\\Models\\' . $file->getFilenameWithoutExtension());

        foreach ($models as $modelClass) {
            $modelName = class_basename($modelClass);

            if (in_array($modelName, $this->exemptedModels)) {
                $this->warn("Skipping exempted model: {$modelName}");
                continue;
            }

            $this->createPolicy($modelClass);
        }

        $this->info('Policies generated successfully.');
    }

    protected function createPolicy(string $modelClass)
    {
        $modelName = class_basename($modelClass);
        $policyName = $modelName . 'Policy';
        $policyPath = app_path("Policies/{$policyName}.php");

        if (File::exists($policyPath)) {
            $this->warn("Policy for {$modelName} already exists. Skipping.");
            return;
        }

        $group = $this->getPermissionGroupForModel($modelName);
        $key = Str::plural(Str::lower($group));

        $stub = $this->buildStub($modelName, $key);

        File::ensureDirectoryExists(app_path('Policies'));
        File::put($policyPath, $stub);

        $this->line("Created: {$policyName}");
    }

    protected function getPermissionGroupForModel(string $modelName): string
    {
        $permission = Permission::where('name', 'like', "%{$modelName}")
            ->orWhere('group', $modelName)
            ->first();

        return $permission?->group ?? $modelName;
    }

    protected function buildStub($model, $key)
    {
        $policyNamespace = "App\\Policies";
        $modelNamespace = "App\\Models\\$model";

        return <<<EOT
<?php

namespace $policyNamespace;

use $modelNamespace;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Policies\BasePolicy;
use Illuminate\Database\Eloquent\Model;

class {$model}Policy extends BasePolicy
{
    use HandlesAuthorization;

    protected \$key = '{$key}';

    protected function buildPermission(string \$name): string
    {
        return \$name . ' ' . \$this->key;
    }
}
EOT;
    }
}