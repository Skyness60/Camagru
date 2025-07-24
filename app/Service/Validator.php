<?php
// app/Service/Validator.php

class Validator
{
    protected $data;
    protected $rules;
    protected $errors = [];

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->validate();
    }

    protected function validate()
    {
        foreach ($this->rules as $field => $rules) {
            foreach ($rules as $rule) {
                $params = null;
                if (strpos($rule, ':') !== false) {
                    [$rule, $params] = explode(':', $rule, 2);
                }
                $method = 'validate' . ucfirst($rule);
                if (method_exists($this, $method)) {
                    $this->$method($field, $params);
                }
            }
        }
    }

    protected function validateRequired($field)
    {
        if (empty($this->data[$field])) {
            $this->addError($field, 'Ce champ est requis.');
        }
    }

    protected function validateEmail($field)
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, 'Email invalide.');
        }
    }

    protected function validateMin($field, $param)
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < (int)$param) {
            $this->addError($field, "Doit contenir au moins $param caractères.");
        }
    }

    protected function validateMatch($field, $param)
    {
        if (!empty($this->data[$field]) && $this->data[$field] !== ($this->data[$param] ?? null)) {
            $this->addError($field, 'Les champs ne correspondent pas.');
        }
    }

    protected function addError($field, $message)
    {
        $this->errors[$field][] = $message;
    }

    public function isValid()
    {
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
