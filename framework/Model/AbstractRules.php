<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Exception\AppException;

abstract class AbstractRules
{
    protected $rules;
    protected $defaultType = null;

    public function __construct()
    {
        $this->rules();
        $this->messages();
    }

    # Method to create rule
    # string $type: type of rule => username|password|email
    # array $rules => [
    #   'min' => 5, 'max' => 16,
    #   'sanitize' => true, 'validate' => true
    # ]
    public function createRule(string $type, array $rules): void
    {
        foreach ($rules as $name => $value) {
            $this->rules[$type][$name]['value'] = $value;
        }
    }

    # Method to create error message for rule
    # string $type: type of rule => username|password|email
    # array $rules => [
    #   'min' => 'This field cannot containt less than x characters'
    #   'sanitize' => 'The email address contains illegal characters'
    # ]
    public function createMessages(string $type, array $rules): void
    {
        foreach ($rules as $name => $message) {
            $this->rules[$type][$name]['message'] = $message;
        }
    }

    # Method returns array of value rules as string.
    # string $name: name of rule like a (avatar.types) <= It must be array
    # Can be created as $rules = [
    #    'types' => ['jpg', 'png', 'jpeg', 'gif'],
    # ]
    public function arrayValue(string $name, bool $uppercase = false): string
    {
        $type = strtok($name, '.');
        $rule = substr($name, strpos($name, '.') + 1);
        $output = '';

        if (!is_array($this->rules[$type][$rule]['value'])) {
            throw new AppException(`Value [$name] nie jest tablicą`);
        }

        foreach ($this->rules[$type][$rule]['value'] as $value) {
            $output .= $value . ', ';
        }

        if ($uppercase) {
            $output = strtoupper($output);
        }
        $output = substr($output, 0, -2);
        return $output;
    }

    # Method check if exists $this->rules with type
    public function hasType(string $type): bool
    {
        if (array_key_exists($type, $this->rules)) {
            return true;
        } else {
            return false;
        }
    }

    # Method set defaultType
    # If defaultType is sets, we don't need send type of rules to methods like a value() or message();
    # $this->setDefaultType("username")
    # $this->value("min") // return x
    # $this->message("min") // return "Username cannot contain less than x characters"
    public function setDefaultType(string $type): void
    {
        if (!$this->hasType($type)) {
            throw new AppException('Wybrany typ nie istnieje');
        }
        $this->defaultType = $type;
    }

    # Method sets defaultType on null.
    public function clearDefaultType(): void
    {
        $this->defaultType = null;
    }

    # Method returns rules of type.
    # Eaxmple: $type = "username" will return { rules, messages } of type username
    public function getType(?string $type = null): array
    {
        if ($type === null) {
            if ($this->defaultType !== null) {
                return $this->rules[$this->defaultType];
            } else {
                throw new AppException('Typ reguły nie został wprowadzony');
            }
        } else {
            if (!$this->hasType($type)) {
                throw new AppException(
                    'Wybrany typ [' . $type . '] nie istnieje'
                );
            } else {
                return $this->rules[$type];
            }
        }
    }

    # Method check if exists $this->rules[$type] with all input rules.
    # Eaxmple: $rules = ['min','max'] | $type = "username"
    public function typeHasRules(array $rules, ?string $type = null): bool
    {
        if ($this->defaultType != null) {
            $type = $this->rules[$this->defaultType];
        } elseif ($type == null) {
            throw new AppException('Typ reguły nie został wprowadzony');
        } elseif (!$this->hasType($type)) {
            throw new AppException('Wybrany typ [' . $type . '] nie istnieje');
        } else {
            $type = $this->rules[$type];
        }

        foreach ($rules as $rule) {
            if (!array_key_exists($rule, $type)) {
                return false;
            }
        }

        return true;
    }

    # Short method to get value of rule
    public function value(?string $name = null)
    {
        return $this->getRule($name)['value'];
    }

    # Short method that returns value of between rule
    # example: password.min | password.max
    public function between(string $name)
    {
        $typeName = strtok($name, '.'); // TypeName
        $limit = substr($name, strpos($name, '.') + 1); // MIN || MAX
        return $this->getRule($typeName . '.between')['value'][$limit];
    }

    # Short method to get error message of rule
    public function message(?string $name = null): ?string
    {
        return $this->getRule($name)['message'];
    }

    # Method returns rule (value + message)
    # Eaxmple: $name = "username.min" will returns { value, message }
    private function getRule(string $name): array
    {
        // example: return username.rules //
        if ($this->defaultType) {
            return $this->getType()[$name]; // Name like a min | max
        } else {
            $typeName = strtok($name, '.');
            $ruleName = substr($name, strpos($name, '.') + 1);

            $type = $this->getType($typeName); // Name like a password.min | password.max

            if ($this->typeHasRules([$ruleName], $typeName)) {
                return $type[$ruleName];
            } else {
                throw new AppException('Wybrana reguła nie istnieje');
            }
        }
    }
}
