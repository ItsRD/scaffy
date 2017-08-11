<?php
namespace ItsRD\Scaffy\Traits;

trait TextHelpers {
    /**
     * Generates a camelcase string of string
     *
     * @author Rick van der Burg <rick@pixcero.nl>
     * @param string $value
     * @return string
     */
    public function camelcase(string $value) : string {
        return camel_case($value);
    }

    /**
     * Adds uppercase to first character
     * @param string $value
     * @return string
     */
    public function uppercase_first(string $value) : string {
        return ucfirst($value);
    }
}