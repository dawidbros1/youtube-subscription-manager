<?php

declare(strict_types=1);

namespace Phantom\Validator;

use Phantom\Helper\Session;

class Validator
{
    private $data;
    private $rules;
    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    # Method check if string has length between $min and $max
    protected function strlenBetween(string $variable, int $min, int $max)
    {
        if (strlen($variable) > $min && strlen($variable) < $max) {
            return true;
        }

        return false;
    }

    # Method check if string has less that $max characters
    protected function strlenMax(string $input, int $max)
    {
        if (strlen($input) > $max) {
            return false;
        }

        return true;
    }

    # Method check if string has more that $min characters
    protected function strlenMin(string $input, int $min)
    {
        if (strlen($input) < $min) {
            return false;
        }

        return true;
    }

    public function validatePassword()
    {
        if (array_key_exists('password', $this->data) && array_key_exists('repeat_password', $this->data)) {
            if ($this->data['password'] != $this->data['repeat_password']) {
                Session::set("error:password:same", "Hasła nie są jednakowe");
                return false;
            }
        }

        return true;
    }

    # Method validate $data
    # array $data: [
    #   'username' => "abc"
    #   'email' => "boom@example.com"
    # ]
    # $rules: object $rules
    public function validate()
    {
        $types = array_keys($this->data);

        foreach ($types as $type) {
            if (!$this->rules->hasType($type)) {
                continue;
            }

            $this->rules->setDefaultType($type);
            $input = $this->data[$type];

            foreach (array_keys($this->rules->getType()) as $rule) {
                $value = $this->rules->value($rule);
                $message = $this->rules->message($rule);

                if ($rule == "between") {
                    $min = $this->rules->value($rule)['min'];
                    $max = $this->rules->value($rule)['max'];

                    if ($this->strlenBetween($input, $min - 1, $max + 1) == false) {
                        $ok = $this->setError($type, $rule, $message);
                    }
                }
                // ================================================
                else if ($rule == "max" && ($this->strlenMax($input, $value) == false)) {
                    $ok = $this->setError($type, $rule, $message);
                }
                // ================================================
                else if ($rule == "min" && ($this->strlenMin($input, $value) == false)) {
                    $ok = $this->setError($type, $rule, $message);
                }
                // ================================================
                else if ($rule == "validate" && $value && (!filter_var($input, FILTER_VALIDATE_EMAIL))) {
                    $ok = $this->setError($type, $rule, $message);
                }
                // ================================================
                else if ($rule == "sanitize" && $value && ($input != filter_var($input, FILTER_SANITIZE_EMAIL))) {
                    $ok = $this->setError($type, $rule, $message);
                }
                // ================================================
                else if ($rule == "require" && $value && (empty($input))) {
                    $ok = $this->setError($type, $rule, $message);
                }
                // ================================================
                else if ($rule == "specialCharacters" && !$value && (preg_match('/[\'^£$%&*()}{@#~"?><>,|=_+¬-]/', $input))) {
                    $ok = $this->setError($type, $rule, $message);
                }
                // ================================================
            }
        }

        return $ok ?? true;
    }

    # Method to validate image
    public function validateImage($FILE, $type)
    {
        $uploadOk = 1;

        if (empty($FILE['name'])) {
            Session::set('error:file:empty', "Nie został wybrany żaden plik");
            return 0;
        }

        $check = getimagesize($FILE["tmp_name"]);

        if ($check === false && $uploadOk) {
            Session::set('error:file:notImage', 'Przesłany plik nie jest obrazem');
            return 0;
        }

        $this->rules->setDefaultType($type);

        if ($this->rules->typeHasRules(['maxSize'])) {
            if (($FILE["size"] >= $this->rules->value('maxSize')) && $uploadOk) {
                Session::set('error:file:maxSize', $this->rules->message('maxSize'));
                $uploadOk = 0;
            }
        }

        if ($this->rules->typeHasRules(['types'])) {
            $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));

            if (!in_array($type, $this->rules->value('types'))) {
                Session::set('error:file:types', $this->rules->message('types'));
                $uploadOk = 0;
            }
        }

        return $uploadOk;
    }

    # Method to set error
    # Eaxmple: "error:username:min"
    private function setError($type, $rule, $message)
    {
        Session::set("error:$type:$rule", $message);
        return false;
    }
}