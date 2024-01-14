<?php

namespace VenderaTradingCompany\PHPActions;

abstract class Action
{
    private $data = [];

    /** Secured keys are only passed between actions and ignore data from request */
    protected $secure = [];

    /** Converts data */
    protected $converter = [];

    /** Validates data */
    protected $validator = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public abstract function handle();

    public function error()
    {
    }

    public function dataFallback(): array
    {
        return [];
    }

    public function _getConverted($converter_class)
    {
        $converter_data = [];

        $tmpData = $this->converter[$converter_class] ?? [];

        foreach ($tmpData as $tmpDataKey => $tmpDataValue) {
            $extractedValue = null;

            if (!empty($tmpDataValue)) {
                if (str_contains($tmpDataValue, '.')) {
                    $explodedTmpDataValue = explode('.', $tmpDataValue);
                    $preData = null;

                    foreach ($explodedTmpDataValue as $key) {
                        if (empty($preData)) {
                            $preData = $this->getData($key);
                        } else if (is_array($preData)) {
                            $preData = $preData[$key] ?? null;
                        } else {
                            $preData = $preData->$key;
                        }
                    }

                    $extractedValue = $preData;
                } else {
                    $extractedValue = $this->getData($tmpDataValue);
                }
            }

            $converter_data[$tmpDataKey] = $extractedValue;
        }

        return Converter::run($converter_class, $converter_data);
    }

    public function getData(string $key, mixed $default = null): mixed
    {
        $data = $this->_getData($key);

        if (is_bool($data)) {
            return $data;
        }

        if ($data == null) {
            return $default;
        }

        return $data;
    }

    private function shouldReturnValue($value)
    {
        if (is_bool($value)) {
            return true;
        }

        if (!empty($value)) {
            return true;
        }

        return false;
    }

    private function _getData(string $key): mixed
    {
        if (class_exists($key)) {
            return $this->_getConverted($key);
        }

        $value = $this->data[$key] ?? null;

        if ($this->shouldReturnValue($value)) {
            return $value;
        }

        $value = $this->dataFallback()[$key] ?? null;

        if ($this->shouldReturnValue($value)) {
            return $value;
        }

        if (in_array($key, $this->secure)) {
            return $value;
        }

        if (function_exists('request')) {
            return request()->$key;
        }

        return null;
    }

    public function validate()
    {
        foreach ($this->validator as $key => $value) {
            $data = $this->getData($key);

            if ($value == 'required') {
                if (is_numeric($data)) {
                    if ($data == 0) {
                        continue;
                    }
                }

                if (is_string($data)) {
                    if ($data == '') {
                        continue;
                    }
                }

                if (is_array($data)) {
                    if (count($data) == 0) {
                        continue;
                    }
                }

                if (empty($data)) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function run(mixed $action, array $data = [], bool $run_synced = false): Response
    {
        $action_class = (new $action($data));

        $response = null;

        if ($action_class->validate()) {
            $response = $action_class->handle();
        } else {
            $response = $action_class->error();
        }

        $response = self::parse_action_handle_response($action, $response);

        if (!$response->isDone()) {
            $errorResponse = $action_class->error();

            $errorResponse = self::parse_action_error_response($action, $errorResponse);

            if (!empty($errorResponse)) {
                $response = $errorResponse;
            }
        }

        if ($response->isDone() && method_exists($action, 'dispatchJob')) {
            $action_class->dispatchJob($response->getData(), $run_synced);

            return Response::done($action, $response);
        }

        return $response;
    }

    private static function parse_action_handle_response($action, $response)
    {
        if (empty($response)) {
            $response = Response::error($action, $response);
        } else {
            if ($response instanceof Response) {
                // $response is instance of Response. Nothing to do.
            } else {
                $response = Response::done($action, $response);
            }
        }

        return $response;
    }

    private static function parse_action_error_response($action, $response)
    {
        if (empty($response)) {
            return null;
        } else {
            if ($response instanceof Response) {
                // $response is instance of Response. Nothing to do.
            } else {
                $response = Response::error($action, $response);
            }
        }

        return $response;
    }
}