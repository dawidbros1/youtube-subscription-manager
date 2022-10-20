<?php

declare (strict_types = 1);

namespace Phantom\Validator;

use Phantom\Helper\Session;

class Validator
{
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

    # Method validate $data
    # array $data: [
    #   'username' => "abc"
    #   'email' => "boom@example.com"
    # ]
    # $rules: object $rules
    public function validate(array $data, $rules)
    {
        $types = array_keys($data);

        # password validate
        if (array_key_exists('password', $data) && array_key_exists('repeat_password', $data)) {
            if ($data['password'] != $data['repeat_password']) {
                Session::set("error:password:same", "Hasła nie są jednakowe");
                $ok = false;
            }
        }

        foreach ($types as $type) {
            if (!$rules->hasType($type)) {continue;}

            $rules->setDefaultType($type);
            $input = $data[$type];

            foreach (array_keys($rules->getType()) as $rule) {
                $value = $rules->value($rule);
                $message = $rules->message($rule);

                if ($rule == "between") {
                    $min = $rules->value($rule)['min'];
                    $max = $rules->value($rule)['max'];

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
    public function validateImage($FILE, $rules, $type)
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

        $rules->setDefaultType($type);

        if ($rules->typeHasRules(['maxSize'])) {
            if (($FILE["size"] >= $rules->value('maxSize')) && $uploadOk) {
                Session::set('error:file:maxSize', $rules->message('maxSize'));
                $uploadOk = 0;
            }
        }

        if ($rules->typeHasRules(['types'])) {
            $type = strtolower(pathinfo($FILE['name'], PATHINFO_EXTENSION));

            if (!in_array($type, $rules->value('types'))) {
                Session::set('error:file:types', $rules->message('types'));
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
