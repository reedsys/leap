<?php

namespace leap;

interface Argument
{
    /**
     * Implement a way of parsing arguments
     *
     * @return an object implementing the \leap\Argument interface
     * @param $options - short options that only contains single character
     * @param $longopts - long options that can be a word
     * @param $input - a string containing the command line arguments
     */
    public static function parse(array $options, array $longopts, string $input = "") : Argument;

    /**
     * Returns a boolean value on a given $key found in an object
     *
     * @return bool - true if a key is found or false if a key is not found
     * @param $key - name of the key
     */
    public function has(string $key) : bool;

    /**
     *
     */
    public function option(string $key, $default = null);

    /**
     *
     */
    public function param(string $key, $default = null);

}

