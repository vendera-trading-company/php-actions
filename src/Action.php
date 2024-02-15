<?php

namespace VenderaTradingCompany\PHPActions;

abstract class Action
{
    private $data = [];

    private $options = [];

    /** Secured values can only be transferred internally. Values from user requests are ignored */
    protected $secure = [];

    /** Converts data */
    protected $converter = [];

    /** Validates data */
    protected $validator = [];

    /** Action id */
    private string | null $_id;

    public function id(): string | null
    {
        return $this->_id;
    }

    public abstract function handle();

    /** Called if validation failed, an empty value or a Boolean 'False' value was returned */
    public function error()
    {
    }

    public function dataFallback(): array
    {
        return [];
    }

    public function _getConverted($converter_class): mixed
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

    public function setId($id): static
    {
        $this->_id = $id;

        return $this;
    }

    public function setOptions($options): static
    {
        $this->options = $options;

        return $this;
    }

    public function setData($data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getOption(string $option): mixed
    {
        $result = $this->options[$option] ?? null;

        if (is_bool($result)) {
            return $result;
        }

        if ($result == null) {
            return $result;
        }

        return $result;
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

    /** Validates all values for this action. Converted values are also validated */
    public function validate()
    {
        foreach ($this->validator as $key => $value) {
            $data = $this->getData($key);

            $result = Validator::run($value, $data);

            if (!$result) {
                return false;
            }
        }

        return true;
    }

    public static function build(mixed $action): Builder
    {
        return new Builder($action);
    }

    public static function run(mixed $action, array $data = []): Response
    {
        return static::build($action)->data($data)->run();
    }

    public static function parse_action_handle_response($action, $response)
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

    public static function parse_action_error_response($action, $response)
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
