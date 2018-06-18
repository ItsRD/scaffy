<?php

namespace ItsRD\Scaffy\Compiler;

class TemplateCompiler implements ICompiler
{
    /**
     * This function compiles a template with "&key&" syntax to regalar text from given data.
     *
     * @author Rick van der Burg <rick@click.nl>
     * @param $template Given template (string)
     * @param $data data which have to translated to text (array)
     * @return string $template
     */
    public function compile($template, $data)
    {
        // foreach through every $data array element
        foreach ($data as $key => $value) {
            // replace "&key& with the given value in given $template
            $template = preg_replace("/\\&$key\\&/i", $value, $template);
        }

        return $template;
    }
}
