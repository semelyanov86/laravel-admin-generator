<?php

namespace App\Console\Commands;

use App\Lib\Scaffold\UcoControllerGenerator;
use App\Lib\Scaffold\UcoMenuGenerator;
use App\Lib\Scaffold\UcoModelGenerator;
use App\Lib\Scaffold\UcoRoutesGenerator;
use App\Lib\Scaffold\UcoViewGenerator;
use App\Permission;
use App\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use InfyOm\Generator\Commands\BaseCommand;
use InfyOm\Generator\Commands\Scaffold\ScaffoldGeneratorCommand;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\FactoryGenerator;
use InfyOm\Generator\Generators\MigrationGenerator;
use InfyOm\Generator\Generators\RepositoryGenerator;
use InfyOm\Generator\Generators\Scaffold\RequestGenerator;
use InfyOm\Generator\Generators\SeederGenerator;
use Symfony\Component\Console\Input\InputOption;

class UcoScaffoldGeneratorCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'uco:scaffold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a full CRUD views for given model from UCO';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->commandData = new CommandData($this, CommandData::$COMMAND_TYPE_SCAFFOLD);
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        if ($this->checkIsThereAnyDataToGenerate()) {
            $this->generateCommonItems();

            $this->generateScaffoldItems();

            $this->addTranslations();
            $this->performPostActionsWithMigration();
            $this->fillDatabase();
        } else {
            $this->commandData->commandInfo('There are not enough input fields for scaffold generation.');
        }
    }

    public function generateCommonItems()
    {
        if (!$this->commandData->getOption('fromTable') and !$this->isSkip('migration')) {
            $migrationGenerator = new MigrationGenerator($this->commandData);
            $migrationGenerator->generate();
        }

        if (!$this->isSkip('model')) {
            $modelGenerator = new UcoModelGenerator($this->commandData);
            $modelGenerator->generate();
        }

        if (!$this->isSkip('repository') && $this->commandData->getOption('repositoryPattern')) {
            $repositoryGenerator = new RepositoryGenerator($this->commandData);
            $repositoryGenerator->generate();
        }

        if ($this->commandData->getOption('factory') || (
                !$this->isSkip('tests') and $this->commandData->getAddOn('tests')
            )) {
            $factoryGenerator = new FactoryGenerator($this->commandData);
            $factoryGenerator->generate();
        }

        if ($this->commandData->getOption('seeder')) {
            $seederGenerator = new SeederGenerator($this->commandData);
            $seederGenerator->generate();
            $seederGenerator->updateMainSeeder();
        }
    }

    private function addTranslations()
    {
        foreach (config('panel.available_languages') as $lang=>$name) {
            $path = resource_path('lang/' . $lang . '/cruds.php');
            $data = file_get_contents($path);
            $newData = Str::replaceLast('// ADD_NEW_CRUD_TRANSLATIONS', $this->getTranslationCrudsValues(), $data);
            if (Str::contains($data, $newData)) {
                $this->commandData->commandObj->info('Translation is already exists, Skipping Adjustment.');

                return;
            }

            file_put_contents($path, $newData);
            $this->commandData->commandComment('Translations added.');
        }
    }

    private function getTranslationCrudsValues()
    {
        $res = "'" . $this->commandData->dynamicVars['$MODEL_NAME_CAMEL$'] . "'        => [
        'title'          => '" . $this->commandData->dynamicVars['$MODEL_NAME_PLURAL$'] . "',
        'title_singular' => '" . $this->commandData->dynamicVars['$MODEL_NAME$'] . "',
        'fields'         => [\n";
        foreach ($this->commandData->fields as $field) {
            $res .= "            '" . $field->name . "'                => '" . Str::title(str_replace('_', ' ', $field->name)) ."',\n";
            $res .= "            '" . $field->name . "_helper'                => '',\n";
        }
        $res .= "        ],\n
    ],
    // ADD_NEW_CRUD_TRANSLATIONS \n";
        return $res;
    }

    private function fillDatabase()
    {
        $model = $this->commandData->dynamicVars['$MODEL_NAME_CAMEL$'];
        $params = array([
                'title' => $model . '_create',
            ],
            [
                'title' => $model . '_edit',
            ],
            [
                'title' => $model . '_show',
            ],
            [
                'title' => $model . '_delete',
            ],
            [
                'title' => $model . '_access',
            ]);
        foreach ($params as $param) {
            $isExisted = Permission::whereTitle($param['title'])->first();
            if (!$isExisted) {
                Permission::create($param);
            }
        }
        $admin_permissions = Permission::all();
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        $user_permissions = $admin_permissions->filter(function ($permission) {
            return substr($permission->title, 0, 5) != 'user_' && substr($permission->title, 0, 5) != 'role_' && substr($permission->title, 0, 11) != 'permission_';
        });
        Role::findOrFail(2)->permissions()->sync($user_permissions);

    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return array_merge(parent::getOptions(), []);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array_merge(parent::getArguments(), []);
    }

    /**
     * Check if there is anything to generate.
     *
     * @return bool
     */
    protected function checkIsThereAnyDataToGenerate()
    {
        if (count($this->commandData->fields) > 1) {
            return true;
        }
    }

    public function generateScaffoldItems()
    {
        if (!$this->isSkip('requests') and !$this->isSkip('scaffold_requests')) {
            $requestGenerator = new RequestGenerator($this->commandData);
            $requestGenerator->generate();
        }

        if (!$this->isSkip('controllers') and !$this->isSkip('scaffold_controller')) {
            $controllerGenerator = new UcoControllerGenerator($this->commandData);
            $controllerGenerator->generate();
        }

        if (!$this->isSkip('views')) {
            $viewGenerator = new UcoViewGenerator($this->commandData);
            $viewGenerator->generate();
        }

        if (!$this->isSkip('routes') and !$this->isSkip('scaffold_routes')) {
            $routeGenerator = new UcoRoutesGenerator($this->commandData);
            $routeGenerator->generate();
        }

        if (!$this->isSkip('menu') and $this->commandData->config->getAddOn('menu.enabled')) {
            $menuGenerator = new UcoMenuGenerator($this->commandData);
            $menuGenerator->generate();
        }
    }
}
