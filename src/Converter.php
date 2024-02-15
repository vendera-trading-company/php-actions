<?php

namespace VenderaTradingCompany\PHPActions;

abstract class Converter
{
    private $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public abstract function handle();

    public function getData(string $key)
    {
        return $this->data[$key] ?? null;
    }

    public static function run(mixed $converter, array $data = [])
    {
        $converter_class = (new $converter($data));

        return $converter_class->handle();
    }
}