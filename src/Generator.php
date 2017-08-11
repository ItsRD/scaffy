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
        'index' => ['views/index.blade.stub', '&view_path&/&plural_name&/index.blade.php'],
        'create' => ['views/create.blade.stub', '&view_path&/&plural_name&/create.blade.php'],
        'edit' => ['views/edit.blade.stub', '&view_path&/&plural_name&/edit.blade.php'],
        'show' => ['views/show.blade.stub', '&view_path&/&plural_name&/show.blade.php'],
        'detail' => ['views/detail.blade.stub', '&view_path&/&plural_name&/detail.blade.php'],
        'controller' => ['Controller.stub', '&controller_path&/&class_name&Controller.php'],
        'resource_controller' => ['ResourceController.stub', '&controller_path&/&class_name&Controller.php'],
        'model' => ['Model.stub', '&model_path&/&class_name&.php']
    ];

    # data
    private $_name,
        $_template;

    private $_compile_data = [],
        $_settings = [];

    # Compiler
    private $_compiler;

    # Messages
    private $_errors = [],
        $_message = [];

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
    public function checkIfInstalled() : bool {
        return file_exists(config('scaffy.scaffy_install')) === true;
    }


    /**
     * Data used in stub files to compile
     * @return void
     */
    private function _compileData() : void
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
    }

    /**
     * Execute scaffold generator to generate all files
     *
     * @param string $name
     * @param string $template
     * @return void
     */
    public function execute(string $name, string $template) : void
    {
        $this->setName($name);
        $this->_setTemplate($template);
        $this->_getSettings();
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
                    if(file_exists(config('scaffy.scaffy_install') .'/'. $this->_getTemplate() .'/'. $from))
                    {
                        $compiled_file_name = $this->_compile($to);
                        $compile_file = $this->_compile($this->_getStubFile($from));
                        $this->_publish($compile_file, $compiled_file_name);
                    } else {
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
     * Get all settings for the given template
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _getSettings() : void
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
    public function _compile($file) : string
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
    private function _generatePath(string $destination) : void
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
    private function _publish(string $file, string $destination) : void
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
    public function hasErrors() : bool {
        return count($this->_errors) > 0;
    }

    /**
     * Get the content of the stub file and return it
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param string $file_name
     * @param string $type
     * @return string
     */
    private function _getStubFile(string $file_name, string $type = null) : string
    {
        return file_get_contents(config('scaffy.scaffy_install') . '/' . $this->_template . '/' . $type . (is_null($type) ? '' : $type . '/') . $file_name);
    }

    /**
     * Add data to the compiled data array
     *
     * @param string $key
     * @param $value
     * @return void
     */
    private function _addCompileData(string $key, $value) : void {
        $this->_compile_data[$key] = $value;
    }

}