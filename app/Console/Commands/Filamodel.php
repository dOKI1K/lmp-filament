<?php

namespace App\Console\Commands;

use Filament\Clusters\Cluster;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Commands\Concerns\CanIndentStrings;
use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Filament\Support\Commands\Concerns\CanReadModelSchemas;
use Filament\Tables\Commands\Concerns\CanGenerateTables;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\label;

class FilamodelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lampminds:filamodel {name?} {--panel=} {--nolmp}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a model and a filament resource';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => 'Which name do you want to use for the filamodel?',
        ];
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (parent::handle() === false && !$this->option('force')) {
            return false;
        }

        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('seed', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
        }

        if ($this->option('factory')) {
            $this->createFactory();
        }

        if ($this->option('migration')) {
            $this->createMigration();
        }

        if ($this->option('seed')) {
            $this->createSeeder();
        }

        if ($this->option('controller') || $this->option('resource') || $this->option('api')) {
            $this->createController();
        }

        $model = (string) str($this->argument('name') ?? text(
            label: 'What is the model name?',
            placeholder: 'Blog',
            required: true,
        ))
            ->studly()
            ->beforeLast('Resource')
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->studly()
            ->replace('/', '\\');

        if (blank($model)) {
            $model = 'Resource';
        }

        $modelNamespace = 'App\\Models';
        $modelClass = (string) str($model)->afterLast('\\');
        $modelSubNamespace = str($model)->contains('\\') ?
            (string) str($model)->beforeLast('\\') :
            '';
        $pluralModelClass = (string) str($modelClass)->pluralStudly();

        $needsAlias = $modelClass === 'Record';

        $hasLampminds = true;

        if ($this->option('nolmp')) {
            $hasLampminds = false;
        }

        $panel = $this->option('panel');

        if ($panel) {
            $panel = Filament::getPanel($panel, isStrict: false);
        }

        if (!$panel) {
            $panels = Filament::getPanels();

            /** @var Panel $panel */
            $panel = (count($panels) > 1) ? $panels[select(
                label: 'Which panel would you like to create this in?',
                options: array_map(
                    fn (Panel $panel): string => $panel->getId(),
                    $panels,
                ),
                default: Filament::getDefaultPanel()->getId()
            )] : Arr::first($panels);
        }

        $resourceDirectories = $panel->getResourceDirectories();
        $resourceNamespaces = $panel->getResourceNamespaces();
        $namespace = (count($resourceNamespaces) > 1) ?
            select(
                label: 'Which namespace would you like to create this in?',
                options: $resourceNamespaces
            ) : (Arr::first($resourceNamespaces) ?? 'App\\Filament\\Resources');
        $path = (count($resourceDirectories) > 1) ?
            $resourceDirectories[array_search($namespace, $resourceNamespaces)] : (Arr::first($resourceDirectories) ?? app_path('Filament/Resources/'));

        $resource = "{$model}Resource";
        $resourceClass = "{$modelClass}Resource";
        $resourceNamespace = $modelSubNamespace;
        $namespace .= $resourceNamespace !== '' ? "\\{$resourceNamespace}" : '';
        $listResourcePageClass = "List{$pluralModelClass}";
        $manageResourcePageClass = "Manage{$pluralModelClass}";
        $createResourcePageClass = "Create{$modelClass}";
        $editResourcePageClass = "Edit{$modelClass}";
        $viewResourcePageClass = "View{$modelClass}";

        $baseResourcePath =
            (string) str($resource)
                ->prepend('/')
                ->prepend($path)
                ->replace('\\', '/')
                ->replace('//', '/');

        $resourcePath = "{$baseResourcePath}.php";
        $resourcePagesDirectory = "{$baseResourcePath}/Pages";
        $listResourcePagePath = "{$resourcePagesDirectory}/{$listResourcePageClass}.php";
        $manageResourcePagePath = "{$resourcePagesDirectory}/{$manageResourcePageClass}.php";
        $createResourcePagePath = "{$resourcePagesDirectory}/{$createResourcePageClass}.php";
        $editResourcePagePath = "{$resourcePagesDirectory}/{$editResourcePageClass}.php";
        $viewResourcePagePath = "{$resourcePagesDirectory}/{$viewResourcePageClass}.php";

        if (!$this->option('force') && $this->checkForCollision([
            $resourcePath,
            $listResourcePagePath,
            $manageResourcePagePath,
            $createResourcePagePath,
            $editResourcePagePath,
            $viewResourcePagePath,
        ])) {
            return static::INVALID;
        }

        $pages = '';
        $pages .= '\'index\' => Pages\\{$listResourcePageClass}::route(\'/\'),';
        $pages .= PHP_EOL . "'create' => Pages\\{$createResourcePageClass}::route('/create'),";
        $pages .= PHP_EOL . "'view' => Pages\\{$viewResourcePageClass}::route('/{record}'),";
        $pages .= PHP_EOL . "'edit' => Pages\\{$editResourcePageClass}::route('/{record}/edit'),";

        $eloquentQuery = '';

        $tableActions = [];
        $tableActions[] = 'Tables\Actions\ViewAction::make(),';
        $tableActions[] = 'Tables\Actions\EditAction::make(),';
        $tableActions[] = 'Tables\Actions\DeleteAction::make(),';
        $tableActions = implode(PHP_EOL, $tableActions);

        $relations = '';
        $relations .= PHP_EOL . 'public static function getRelations(): array';
        $relations .= PHP_EOL . '{';
        $relations .= PHP_EOL . '    return [';
        $relations .= PHP_EOL . '        //';
        $relations .= PHP_EOL . '    ];';
        $relations .= PHP_EOL . '}' . PHP_EOL;

        $tableBulkActions = [];
        $tableBulkActions[] = 'Tables\Actions\DeleteBulkAction::make(),';
        $tableBulkActions = implode(PHP_EOL, $tableBulkActions);

        $this->copyStubToApp('Resource', $resourcePath, [
            'eloquentQuery' => $this->indentString($eloquentQuery, 1),
            'model' => ($model === 'Resource') ? "{$modelNamespace}\\Resource as ResourceModel" : "{$modelNamespace}\\{$model}",
            'modelClass' => ($model === 'Resource') ? 'ResourceModel' : $modelClass,
            'namespace' => $namespace,
            'pages' => $this->indentString($pages, 3),
            'relations' => $this->indentString($relations, 1),
            'resource' => "{$namespace}\\{$resourceClass}",
            'resourceClass' => $resourceClass,
            'tableActions' => $this->indentString($tableActions, 4),
            'tableBulkActions' => $this->indentString($tableBulkActions, 5),
        ]);

        $this->copyStubToApp('ResourceListPage', $listResourcePagePath, [
            'baseResourcePage' => 'Filament\\Resources\\Pages\\ListRecords' . ($needsAlias ? ' as BaseListRecords' : ''),
            'baseResourcePageClass' => $needsAlias ? 'BaseListRecords' : 'ListRecords',
            'namespace' => "{$namespace}\\{$resourceClass}\\Pages",
            'resource' => "{$namespace}\\{$resourceClass}",
            'resourceClass' => $resourceClass,
            'resourcePageClass' => $listResourcePageClass,
        ]);

        $this->copyStubToApp('ResourcePage', $createResourcePagePath, [
            'baseResourcePage' => 'Filament\\Lampminds\\Resources\\LmpCreateRecord',
            'baseResourcePageClass' => $hasLampminds ? 'LmpCreateRecord' : 'CreateRecord',
            'namespace' => "{$namespace}\\{$resourceClass}\\Pages",
            'resource' => "{$namespace}\\{$resourceClass}",
            'resourceClass' => $resourceClass,
            'resourcePageClass' => $createResourcePageClass,
        ]);

        $this->copyStubToApp('ResourceViewPage', $viewResourcePagePath, [
            'baseResourcePage' => 'Filament\\Resources\\Pages\\ViewRecord' . ($needsAlias ? ' as BaseViewRecord' : ''),
            'baseResourcePageClass' => $needsAlias ? 'BaseViewRecord' : 'ViewRecord',
            'namespace' => "{$namespace}\\{$resourceClass}\\Pages",
            'resource' => "{$namespace}\\{$resourceClass}",
            'resourceClass' => $resourceClass,
            'resourcePageClass' => $viewResourcePageClass,
        ]);

        $editPageActions = [];
        $editPageActions[] = 'Actions\ViewAction::make(),';
        $editPageActions[] = 'Actions\DeleteAction::make(),';
        $editPageActions = implode(PHP_EOL, $editPageActions);

        $this->copyStubToApp('ResourceEditPage', $editResourcePagePath, [
            'baseResourcePage' => 'Filament\\Lampminds\\Resources\\LmpEditRecord',
            'baseResourcePageClass' => $hasLampminds ? 'LmpEditRecord' : 'EditRecord',
            'actions' => $this->indentString($editPageActions, 3),
            'namespace' => "{$namespace}\\{$resourceClass}\\Pages",
            'resource' => "{$namespace}\\{$resourceClass}",
            'resourceClass' => $resourceClass,
            'resourcePageClass' => $editResourcePageClass,
        ]);

        $this->components->info("Filament resource [{$resourcePath}] created successfully.");

        return static::SUCCESS;
    }

    protected function createFactory()
    {
        $factory = Str::studly($this->argument('name'));

        $this->call('make:factory', [
            'name' => "{$factory}Factory",
            '--model' => $this->qualifyClass($this->getNameInput()),
        ]);
    }

    /**
     * Create a migration file for the model.
     *
     * @return void
     */
    protected function createMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * Create a seeder file for the model.
     *
     * @return void
     */
    protected function createSeeder()
    {
        $seeder = Str::studly(class_basename($this->argument('name')));

        $this->call('make:seeder', [
            'name' => "{$seeder}Seeder",
        ]);
    }


    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('make:controller', array_filter([
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
            '--requests' => $this->option('requests') || $this->option('all'),
            '--test' => $this->option('test'),
            '--pest' => $this->option('pest'),
        ]));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/model.stub');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, seeder, factory, and resource controller for the model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
            ['seed', 's', InputOption::VALUE_NONE, 'Create a new seeder for the model'],
        ];
    }
}
