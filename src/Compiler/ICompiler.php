<?php

namespace ItsRD\Scaffy\Compiler;

/**
 * Interface for Compilers.
 * @author Rick van der Burg <rick@click.nl>
 */
interface ICompiler
{
    public function compile($template, $data);
}
