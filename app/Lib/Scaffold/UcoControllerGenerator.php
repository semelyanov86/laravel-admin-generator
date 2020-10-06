<?php


namespace App\Lib\Scaffold;

use Illuminate\Support\Str;
use InfyOm\Generator\Common\CommandData;
use InfyOm\Generator\Generators\BaseGenerator;
use InfyOm\Generator\Utils\FileUtil;


class UcoControllerGenerator extends \InfyOm\Generator\Generators\Scaffold\ControllerGenerator
{
    /** @var CommandData */
    private $commandData;

    /** @var string */
    private $path;

    /** @var string */
    private $templateType;

    /** @var string */
    private $fileName;

    private $rawColumns = array("'actions'", "'placeholder'");

    public function __construct(CommandData $commandData)
    {
        $this->commandData = $commandData;
        $this->path = $commandData->config->pathController;
        $this->templateType = config('infyom.laravel_generator.templates', 'adminlte-templates');
        $this->fileName = $this->commandData->modelName.'Controller.php';
    }

    public function generate()
    {
        if ($this->commandData->getAddOn('datatables')) {
            if ($this->commandData->getOption('repositoryPattern')) {
                $templateName = 'datatable_controller';
            } else {
                $templateName = 'model_datatable_controller';
            }

            if ($this->commandData->isLocalizedTemplates()) {
                $templateName .= '_locale';
            }

            $templateData = get_template("scaffold.controller.$templateName", 'laravel-generator');

            $templateData = $this->generateDataTable($templateData);
        } else {
            if ($this->commandData->getOption('repositoryPattern')) {
                $templateName = 'controller';
            } else {
                $templateName = 'model_controller';
            }
            if ($this->commandData->isLocalizedTemplates()) {
                $templateName .= '_locale';
            }

            $templateData = get_template("scaffold.controller.$templateName", 'laravel-generator');

            $paginate = $this->commandData->getOption('paginate');

            if ($paginate) {
                $templateData = str_replace('$RENDER_TYPE$', 'paginate('.$paginate.')', $templateData);
            } else {
                $templateData = str_replace('$RENDER_TYPE$', 'all()', $templateData);
            }
        }

        $templateData = fill_template($this->commandData->dynamicVars, $templateData);

        FileUtil::createFile($this->path, $this->fileName, $templateData);

        $this->commandData->commandComment("\nController created: ");
        $this->commandData->commandInfo($this->fileName);
    }

    private function generateDataTable($templateData)
    {
        $templateData = str_replace(
            '$DATATABLE_COLUMNS$',
            implode(''.infy_nl_tab(1, 3), $this->generateDataTableColumns()),
            $templateData
        );
        $templateData = str_replace(
            '$RAW_DATATABLE_COLUMNS$',
            implode(','.infy_nl_tab(1, 3), $this->rawColumns),
            $templateData
        );
        $this->commandData->commandComment("\nDataTable created: ");

        return $templateData;
    }

    private function generateDataTableColumns()
    {
        $templateName = 'datatable_column_controller';
        if ($this->commandData->isLocalizedTemplates()) {
            $templateName .= '_locale';
        }
        $headerFieldTemplate = get_template('scaffold.views.'.$templateName, $this->templateType);

        $dataTableColumns = [];
        foreach ($this->commandData->fields as $field) {
            if (!$field->inIndex) {
                continue;
            }
            if ($field->htmlType == 'selectTable') {
                $fieldData = explode(':', $field->htmlInput);
                $fieldName = trim($field->name, '_id');
                $displayValueArr = explode(',', $fieldData[2]);
                $headerFieldTemplateParsed = str_replace('$FIELD_NAME$', $fieldName . '_' . $displayValueArr[0], $headerFieldTemplate);
                $headerFieldTemplateParsed = str_replace(
                    '$DATATABLE_FIELD_CONTENT$',
                    'return $row->' . $fieldName . ' ? $row->' . $fieldName . '->' . $displayValueArr[0] . ' : \'\';',
                    $headerFieldTemplateParsed);
                $this->rawColumns[] = "'" . $fieldName . "'";
            } elseif ($field->htmlType == 'select') {
                $headerFieldTemplateParsed = str_replace('$DATATABLE_FIELD_CONTENT$', 'return $row->' . $field->name . ' ? $MODEL_NAME$::' . Str::upper($field->name) . '_SELECT[$row->' . $field->name . '] : "";', $headerFieldTemplate);
            } elseif($field->htmlType == 'checkbox') {
                $headerFieldTemplateParsed = str_replace('$DATATABLE_FIELD_CONTENT$', 'return \'<input type="checkbox" disabled \' . ($row->' . $field->name . ' ? \'checked\' : null) . \'>\';', $headerFieldTemplate);
                $this->rawColumns[] = "'" . $field->name . "'";
            } else {
                $headerFieldTemplateParsed = str_replace('$DATATABLE_FIELD_CONTENT$', 'return $row->' . $field->name . ' ? $row->' . $field->name . ' : "";', $headerFieldTemplate);
            }

            $fieldTemplate = fill_template_with_field_data(
                $this->commandData->dynamicVars,
                $this->commandData->fieldNamesMapping,
                $headerFieldTemplateParsed,
                $field
            );
            $dataTableColumns[] = $fieldTemplate;

        }

        return $dataTableColumns;
    }

    public function rollback()
    {
        if ($this->rollbackFile($this->path, $this->fileName)) {
            $this->commandData->commandComment('Controller file deleted: '.$this->fileName);
        }

        if ($this->commandData->getAddOn('datatables')) {
            if ($this->rollbackFile(
                $this->commandData->config->pathDataTables,
                $this->commandData->modelName.'DataTable.php'
            )) {
                $this->commandData->commandComment('DataTable file deleted: '.$this->fileName);
            }
        }
    }
}
