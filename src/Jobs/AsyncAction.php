<?php

namespace VenderaTradingCompany\PHPActions\Jobs;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;

abstract class AsyncAction implements ShouldBeUnique, ShouldQueue
{
    protected array $_data = [];

    public function __construct(array | null $data = [])
    {
        $this->_data = $data ?? [];
    }

    public function getData(string $key): mixed
    {
        $data = $this->_data['data'] ?? [];

        return $data[$key] ?? null;
    }

    public function data(): array
    {
        return $this->_data;
    }
}
