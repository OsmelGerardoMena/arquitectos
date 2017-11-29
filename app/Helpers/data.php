<?php

if (!function_exists('ifempty'))
{
    /**
     * print_object
     * Filtra un objeto si este esta vacio.
     *
     * @param object $object
     * @param string $replace
     * @return string
     */
    function ifempty($object, $replace = '-') {

        $output = $object;

        if (empty($output)) {
            $output = $replace;
        }

        return $output;
    }
}

if (!function_exists('ifset'))
{
    /**
     * print_object
     * Filtra un objeto si este esta vacio.
     *
     * @param object $object
     * @param string $replace
     * @return string
     */
    function ifset($object, $replace = '') {

        if (isset($object)) {
            return $object;
        }

        return $replace;
    }
}

