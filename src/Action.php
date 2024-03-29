<?php

namespace VenderaTradingCompany\PHPActions;

abstract class Action
{
    /** Data */
    private $_data = [];

    /** Options */
    public $_options = [];

    /** Available options */
    protected $options = [];

    /** Secured values can only be transferred internally. Values from user requests are ignored */
    protected $secure = [];

    /** Converts data */
    protected $converter = [];

    /** Validates data */
    protected $validator = [];

    /** Action id */
    public string | null $id;

    public abstract function handle();

    /** Called if validation failed, an empty value or a Boolean 'False' value was returned */
    public function error()
    {
    }

    public function dataFallback(): array
    {
        return [];
    }

    public function _getConverted(mixed $converter_class): mixed
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

    public function setId(string | null $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setOptions(array | null $options): static
    {
        $this->_options = $options;

        return $this;
    }

    public function setData(array | null $data): static
    {
        $this->_data = $data;

        return $this;
    }

    public function getOption(string $option, mixed $default = null): mixed
    {
        $result = $this->_options[$option] ?? null;

        if (array_key_exists($option, $this->options)) {
            $expected = $this->options[$option];

            if (!in_array($result, $expected)) {
                return $default ?? $expected[0];
            }
        }

        if (is_bool($result)) {
            return $result;
        }

        if ($result == null) {
            return $default;
        }

        return $result;
    }

    public function matchOption(string $option, mixed $expected, mixed $default = null): bool
    {
        return $this->getOption($option, $default) == $expected;
    }

    private function shouldReturnValue(mixed $value)
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

        $value = $this->_data[$key] ?? null;

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
