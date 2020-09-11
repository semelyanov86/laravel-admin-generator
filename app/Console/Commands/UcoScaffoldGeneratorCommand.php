<?php

namespace App\Console\Commands;

use App\Lib\Scaffold\UcoMenuGenerator;
use App\Lib\Scaffold\UcoRoutesGenerator;
use App\Permission;
use App\Role;
use Illuminate\Console\Command;
use InfyOm\Generator\Commands\BaseCommand;
use InfyOm\Generator\Commands\Scaffold\ScaffoldGeneratorCommand;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\Scaffold\ControllerGenerator;
use InfyOm\Generator\Generators\Scaffold\MenuGenerator;
use InfyOm\Generator\Generators\Scaffold\RequestGenerator;
use InfyOm\Generator\Generators\Scaffold\RoutesGenerator;
use InfyOm\Generator\Generators\Scaffold\ViewGenerator;
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

            $this->performPostActionsWithMigration();
            $this->fillDatabase();
        } else {
            $this->commandData->commandInfo('There are not enough input fields for scaffold generation.');
        }
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
            $controllerGenerator = new ControllerGenerator($this->commandData);
            $controllerGenerator->generate();
        }

        if (!$this->isSkip('views')) {
            $viewGenerator = new ViewGenerator($this->commandData);
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
