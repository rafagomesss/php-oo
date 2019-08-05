<?php
namespace Code\Security\Validator;

class Validator
{
    public static function validateRequiredFields(array $data): bool
    {
        foreach ($data as $key => $value) {
            if (is_null($data[$key]) || empty($data[$key])) {
                return false;
                break;
            }
        }
        return true;
    }
}