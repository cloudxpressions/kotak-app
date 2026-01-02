<?php

namespace App\Core;

class Validator
{
    private $errors = [];

    public function validate($data, $rules)
    {
        $this->errors = [];

        foreach ($rules as $field => $rule) {
            $rulesArray = explode('|', $rule);
            $value = $data[$field] ?? null;

            foreach ($rulesArray as $ruleItem) {
                $ruleParts = explode(':', $ruleItem);
                $ruleName = $ruleParts[0];

                switch ($ruleName) {
                    case 'required':
                        if ($this->isEmpty($value)) {
                            $this->addError($field, "The {$field} field is required.");
                        }
                        break;
                    case 'email':
                        if (!$this->isEmpty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->addError($field, "The {$field} must be a valid email address.");
                        }
                        break;
                    case 'min':
                        $minLength = (int)$ruleParts[1];
                        if (!$this->isEmpty($value) && strlen($value) < $minLength) {
                            $this->addError($field, "The {$field} must be at least {$minLength} characters.");
                        }
                        break;
                    case 'max':
                        $maxLength = (int)$ruleParts[1];
                        if (!$this->isEmpty($value) && strlen($value) > $maxLength) {
                            $this->addError($field, "The {$field} may not be greater than {$maxLength} characters.");
                        }
                        break;
                    case 'confirmed':
                        $confirmField = $field . '_confirmation';
                        if (!isset($data[$confirmField]) || $value !== $data[$confirmField]) {
                            $this->addError($field, "The {$field} confirmation does not match.");
                        }
                        break;
                    case 'unique':
                        $table = $ruleParts[1];
                        $column = $ruleParts[2] ?? $field;
                        if (!$this->isEmpty($value) && $this->valueExists($table, $column, $value)) {
                            $this->addError($field, "The {$field} has already been taken.");
                        }
                        break;
                    case 'exists':
                        $table = $ruleParts[1];
                        $column = $ruleParts[2] ?? $field;
                        if (!$this->isEmpty($value) && !$this->valueExists($table, $column, $value)) {
                            $this->addError($field, "The selected {$field} is invalid.");
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    private function isEmpty($value)
    {
        return $value === null || $value === '';
    }

    private function addError($field, $message)
    {
        $this->errors[$field] = $message;
    }

    private function valueExists($table, $column, $value)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM {$table} WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetchColumn() > 0;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function firstError()
    {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
}