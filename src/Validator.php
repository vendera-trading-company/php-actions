<?php

namespace VenderaTradingCompany\PHPActions;

class Validator
{
    public static function run(mixed $validator, mixed $data): bool
    {
        if ($validator == 'required') {
            if (is_numeric($data)) {
                if ($data == 0) {
                    return true;
                }
            }

            if (is_string($data)) {
                if ($data == '') {
                    return true;
                }
            }

            if (is_array($data)) {
                if (count($data) == 0) {
                    return true;
                }
            }

            if (empty($data)) {
                return false;
            }
        }

        return true;
    }
}
