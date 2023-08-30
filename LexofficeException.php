<?php

namespace BaebecaSolutions\LexofficePhpApi;
use Exception;

include_once __DIR__ . "/vendor/autoload.php";
class LexofficeException extends Exception
{
    private $custom_error;

    public function __construct($message, $data = [])
    {
        $this->custom_error = $data;
        parent::__construct($message);
    }

    public function get_error()
    {
        return $this->custom_error;
    }
}