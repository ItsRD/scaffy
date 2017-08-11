<?php

namespace ItsRD\Scaffy\Traits;

/**
 * This Trait adds getters and setters for variables.
 * Trait GetSetters
 * @package ItsRD\Scaffolder\Traits
 */
trait GetSetters {

    /**
     * Set the name of the scaffold
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param string $name
     * @return void
     */
    public function setName(string $name) : void {
        $this->_name = $name;
        $this->_addCompileData('name', $name);
    }

    /**
     * Get name
     * @return string
     */
    public function getName() : string {
        return $this->_name;
    }

    /**
     * Set custom files to publish with scaffolding
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _setFiles() : void {
        $custom_files = $this->_settings['custom_files'];
        if(!empty($custom_files))
        {
            foreach($custom_files as $name=>$file) {
                $this->_files[$name] = $file;
            }
        }
    }

    /**
     * Set template of the scaffold
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param string $template
     * @return void
     */
    private function _setTemplate(string $template) : void {
        $this->_template = $template;
    }

    private function _getTemplate() : string {
        return $this->_template;
    }

    /**
     * Sets the classname camelcase
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param string $name
     * @return void
     */
    private function _setClassName(string $name) : void {
        $this->_addCompileData('class_name', $this->uppercase_first($this->camelcase($name)));
    }

    /**
     * Sets the classname to snakecase
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param string $name
     * @return void
     */
    private function _setSnakeCaseName(string $name) : void {
        $this->_addCompileData('snake_name', snake_case($name));
    }

    /**
     * Set the params set by the user
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _setParams() : void {
        foreach($this->_settings['params'] as $key=>$value) {
            $this->_addCompileData($key, $value);
        }
    }

    /**
     * Set the plural name (e.g. database names)
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _setPluralName() : void {
        $this->_addCompileData('plural_name', str_plural($this->getName()));
    }
    /**
     * Set view path
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _setViewPath() : void {
        $this->_addCompileData('view_path', str_replace(base_path(), '', $this->_settings['view_path']));
    }
    /**
     * Set controller path
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _setControllerPath() : void {
        $this->_addCompileData('controller_path', str_replace(base_path(), '', $this->_settings['controller_path']));
    }

    /**
     * Set the controller namespace
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _setControllerNamespace() : void {
        $this->_addCompileData('controller_ns', $this->uppercase_first(substr(str_replace('/', '\\', $this->_compile_data['controller_path']), 1)));
    }

    /**
     * Set model path
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _setModelPath() : void {
        $this->_addCompileData('model_path', str_replace(base_path(), '', $this->_settings['model_path']));
    }

    /**
     * Set model namespace
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _setModelNamespace() : void {
        $this->_addCompileData('model_ns', $this->uppercase_first(substr(str_replace('/', '\\', $this->_compile_data['model_path']), 1)));
    }

    /**
     * Set request path
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _setRequestPath() : void {
        $this->_addCompileData('request_path', str_replace(base_path(), '', $this->_settings['request_path']));
    }

    /**
     * Set request namespace
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @return void
     */
    private function _setRequestNamespace() : void {
        $this->_addCompileData('request_ns', $this->uppercase_first(substr(str_replace('/', '\\', $this->_compile_data['request_path']), 1)));
    }

    /**
     * Get all errors
     * @return array
     */
    public function getErrors() : array
    {
        return $this->_errors;
    }

    /**
     * Get messages
     * @return array
     */
    public function getMessages() : array
    {
        return $this->_message;
    }

    /**
     * Get all files used for scaffolding by template
     * @return array
     */
    public function getFiles() : array {
        return $this->_files;
    }
}