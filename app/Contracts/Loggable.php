<?php
namespace App\Contracts;

interface Loggable{
    public function getLog();
    public function getLogFor(string $fieldName);

}
