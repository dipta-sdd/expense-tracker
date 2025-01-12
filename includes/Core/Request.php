<?php

namespace ExpenseTracker\Core;

class Request
{
    private $data = [];
    private $errors = [];
    private $validated = true;
    private $messages = [
        'required' => 'This field is required.',
        'email' => 'This must be a valid email address.',
        'min' => 'This must be at least :min characters.',
        'max' => 'This must be at most :max characters.',
        'numeric' => 'This must be a number.',
        'alpha' => 'This must only contain letters.',
        'alpha_num' => 'This must only contain letters and numbers.',
        'alpha_dash' => 'This must only contain letters, numbers, dashes and underscores.',
        'date' => 'This is not a valid date.',
        'url' => 'This must be a valid URL.',
        'array' => 'This must be an array.',
        'boolean' => 'This field must be true or false.',
        'confirmed' => 'This confirmation does not match.',
        'integer' => 'This must be an integer.',
        'string' => 'This must be a string.',
        'unique' => 'This has already been taken.',
        'exists' => 'The selected :attribute is invalid.',
        'in' => 'The selected :attribute is invalid.',
        'not_in' => 'The selected :attribute is invalid.',
        'between' => 'This must be between :min and :max.',
        'size' => 'This must be :size.'
    ];

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function validate($rules)
    {
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);
            $isNullable = in_array('nullable', $rulesArray);
            $value = $this->get_field_value($field);

            // Skip validation if field is nullable and value is empty
            if ($isNullable && ($value === '' || $value === null)) {
                continue;
            }

            foreach ($rulesArray as $rule) {
                // Skip the nullable rule itself
                if ($rule === 'nullable') {
                    continue;
                }

                // Split rule into name and parameters
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $parameters = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];

                // Validate the field
                if (!$this->validate_field($ruleName, $value, $parameters)) {
                    $this->add_error($field, $ruleName, $parameters);
                    break;
                }
            }
        }

        return empty($this->errors);
    }

    private function validate_field($rule, $value, $parameters)
    {
        switch ($rule) {
            case 'required':
                return !empty($value);
            case 'email':
                return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
            case 'min':
                return strlen($value) >= $parameters[0];
            case 'max':
                return strlen($value) <= $parameters[0];
            case 'numeric':
                return is_numeric($value);
            case 'array':
                return is_array($value);
            case 'url':
                return filter_var($value, FILTER_VALIDATE_URL) !== false;
            default:
                return true;
        }
    }

    private function get_field_value($field)
    {
        $keys = explode('.', $field);
        $value = $this->data;

        foreach ($keys as $key) {
            if (isset($value[$key])) {
                $value = $value[$key];
            } else {
                return null;
            }
        }

        return $value;
    }

    private function add_error($field, $rule, $parameters)
    {
        $message = $this->messages[$rule];

        // Replace placeholders
        $message = str_replace(':attribute', $field, $message);
        if (!empty($parameters)) {
            $message = str_replace(':min', $parameters[0], $message);
            $message = str_replace(':max', $parameters[0], $message);
        }

        $this->errors[$field] = $message;
        $this->validated = false;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function isValid()
    {
        return $this->validated;
    }

    public function all()
    {
        return $this->data;
    }
}
