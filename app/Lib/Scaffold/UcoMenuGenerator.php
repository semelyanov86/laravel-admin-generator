<?php

namespace App\Lib\Scaffold;

use Illuminate\Support\Str;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\Scaffold\MenuGenerator;

class UcoMenuGenerator extends MenuGenerator
{
    const PLACEHOLDER_VALUE = '{{--   UCO SCAFFOLD PLACEHOLDER     --}}';
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $templateType;

    /** @var string */
    private $menuContents;

    /** @var string */
    private $menuTemplate;

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = config(
                'infyom.laravel_generator.path.views',
                resource_path(
                    'views/'
                )
            ).$commandData->getAddOn('menu.menu_file');
        $this->templateType = config('infyom.laravel_generator.templates', 'adminlte-templates');

        $this->menuContents = file_get_contents($this->path);

        $templateName = 'menu_template';

        if ($this->commandData->isLocalizedTemplates()) {
            $templateName .= '_locale';
        }

        $this->menuTemplate = get_template('scaffold.layouts.'.$templateName, $this->templateType);

        $this->menuTemplate = fill_template($this->commandData->dynamicVars, $this->menuTemplate);
    }
    public function generate()
    {
        $this->menuContents = Str::replaceLast(self::PLACEHOLDER_VALUE, $this->menuTemplate.infy_nl(), $this->menuContents);
        $existingMenuContents = file_get_contents($this->path);
        if (Str::contains($existingMenuContents, '<span>'.$this->commandData->config->mHumanPlural.'</span>')) {
            $this->commandData->commandObj->info('Menu '.$this->commandData->config->mHumanPlural.' is already exists, Skipping Adjustment.');

            return;
        }

        file_put_contents($this->path, $this->menuContents);
        $this->commandData->commandComment("\n".$this->commandData->config->mCamelPlural.' menu added.');
    }
}
