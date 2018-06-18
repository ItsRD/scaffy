<?php

namespace ItsRD\Scaffy;

use ItsRD\Scaffy\Compiler\TemplateCompiler;
use ItsRD\Scaffy\Traits\GetSetters;
use ItsRD\Scaffy\Traits\TextHelpers;
use Illuminate\Support\Facades\File;

class Generator
{
    use GetSetters, TextHelpers;

    # Files
    private $_files = [
        'index' => ['views/index.blade.stub', '&view_path&/&slugged_name&/index.blade.php'],
        'create' => ['views/create.blade.stub', '&view_path&/&slugged_name&/create.blade.php'],
        'edit' => ['views/edit.blade.stub', '&view_path&/&slugged_name&/edit.blade.php'],
        'show' => ['views/show.blade.stub', '&view_path&/&slugged_name&/show.blade.php'],
        'detail' => ['views/detail.blade.stub', '&view_path&/&slugged_name&/detail.blade.php'],
        'controller' => ['Controller.stub', '&controller_path&/&class_name&Controller.php'],
        'resource_controller' => ['ResourceController.stub', '&controller_path&/&class_name&Controller.php'],
        'model' => ['Model.stub', '&model_path&/&class_name&.php']
    ];

    # data
    private $_name,
            $_template,
            $_template_path;

    private $_compile_data = [],
            $_settings = [];

    # Compiler
    private $_compiler;

    # Messages
    private $_errors = [],
            $_message = [],
            $_path_exists;

    /**
     * Generator constructor
     */
    public function __construct()
    {
        $this->_compiler = new TemplateCompiler();
    }

    /**
     * Check wether scaffy is installed or not
     * @return bool
     */
    public function checkIfInstalled()  {
        return file_exists(array_first(config('scaffy.scaffy_install'))) === true;
    }


    /**
     * Data used in stub files to compile
     * @return void
     */
    private function _compileData()
    {
        $name = $this->_name;

        $this->_setClassName($name);
        $this->_setSnakeCaseName($name);
        $this->_setControllerPath();
        $this->_setControllerNamespace();
        $this->_setViewPath();
        $this->_setModelPath();
        $this->_setModelNamespace();
        $this->_setPluralName();
        $this->_setRequestPath();
        $this->_setRequestNamespace();
        $this->_setSluggedName();
    }

    /**
     * Execute scaffold generator to generate all files
     *
     * @param string $name
     * @param string $template
     * @return void
     */
    public function execute(string $name, string $template)
    {
        if($template === "default") {
            $template = config('scaffy.template');
        }

        $this->setName($name);
        $this->_setTemplate($template);
        $this->_getSettings();
        $this->_setTemplatePath();
        $this->_setFiles();
        $this->_setParams();

        
        $this->_compileData();

        if(empty($this->getErrors()))
        {
            foreach ($this->_settings['files'] as $file)
            {
                if(array_key_exists($file, $this->getFiles()))
                {
                    $from = $this->getFiles()[$file][0];
                    $to = $this->getFiles()[$file][1];

                    foreach(config('scaffy.scaffy_install') as $path) {
                        $this->_generateFiles($path, $from, $to);
                    }

                    if($this->_path_exists !== true) {
                        $this->_errors[] = "File '{$from}' does not exists, please create this file to scaffold.";
                        return;
                    }
                } else {
                    $this->_errors[] = "{$file} does not exists in files, please check the documentation to create one your own.";
                    return;
                }
            }
        }
    }

    /**
     * Generate all files
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param string $path
     * @param string $from
     * @param string $to
     * @return void
     */
    private function _generateFiles(string $path, string $from, string $to)
    {
        if(file_exists($path .'/'. $this->_getTemplatePath() .'/'. $from)) {
            $compiled_file_name = $this->_compile($to);
            $compile_file = $this->_compile($this->_getStubFile($path, $from));
            $this->_publish($compile_file, $compiled_file_name);
            $this->_path_exists = true;
        } elseif(!$this->_path_exists) {
            $this->_path_exists[$from] = $from;
        }
    }

    /**
     * Get all settings for the given template
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _getSettings()
    {
        $template_settings = config('scaffy.templates.'. $this->_template);
        if(isset($template_settings)) {
            $this->_settings = $template_settings;
        } else {
            $this->_errors[] = 'config file does not exists or template settings are not present in config file.';
        }
    }

    /**
     * Compile file with compiling data
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param $file
     * @return string
     */
    public function _compile($file)
    {
        return $this->_compiler->compile($file, $this->_compile_data);
    }

    /**
     * Generates the missing folders
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param string $destination
     * @return void
     */
    private function _generatePath(string $destination)
    {
        $array_of_paths = collect(explode('/', $destination));
        $already_checked_path = '';
        foreach ($array_of_paths as $path_item) {
            if (!file_exists(base_path($already_checked_path . '/' . $path_item))) {
                if (strpos($path_item, '.php') === false) {
                    File::makeDirectory(base_path($already_checked_path . '/' . $path_item));
                }
            }
            $already_checked_path .= '/' . $path_item;
        };
    }

    /**
     * Publish a file with compiled data to the destination folder
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param string $file
     * @param string $destination
     * @return void
     */
    private function _publish(string $file, string $destination)
    {
        if (!file_exists(base_path($destination))) {
            $this->_generatePath($destination);
            file_put_contents(base_path($destination), $file);
            $this->_message[] = $destination . ' has been added succesfully.';
        } else {
            $this->_message[] = $destination . ' already exists. Skipping file.';
        }
    }

    /**
     * Check if generators has any errors
     * @return bool
     */
    public function hasErrors() {
        return count($this->_errors) > 0;
    }

    /**
     * Get the content of the stub file and return it
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param string $destination_path
     * @param string $file_name
     * @param string $type
     * @return string
     */
    private function _getStubFile(string $destination_path, string $file_name, string $type = null)
    {
        return file_get_contents($destination_path . '/' . $this->_getTemplatePath() . '/' . $type . (is_null($type) ? '' : $type . '/') . $file_name);
    }

    /**
     * Add data to the compiled data array
     *
     * @param string $key
     * @param $value
     * @return void
     */
    private function _addCompileData(string $key, $value)  {
        $this->_compile_data[$key] = $value;
    }

}
