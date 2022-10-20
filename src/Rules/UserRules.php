<?php

declare (strict_types = 1);

namespace App\Rules;

use Phantom\Model\AbstractRules;

class UserRules extends AbstractRules
{
    public function rules()
    {
        $this->createRule('username', ['between' => ['min' => 3, "max" => 16], 'specialCharacters' => false]);
        $this->createRule('password', ['between' => ['min' => 6, 'max' => 36]]);
        $this->createRule('email', ['sanitize' => true, "validate" => true]);

        $this->createRule('avatar', [
            'maxSize' => 512 * 512,
            'types' => ['jpg', 'png', 'jpeg', 'gif'],
        ]);

        $this->createRule('regulations', ['require' => true]);
    }

    public function messages()
    {
        $this->createMessages('username', [
            'between' => "Nazwa użytkownika powinna zawierać od " . $this->between('username.min') . " do " . $this->between('username.max') . " znaków",
            'specialCharacters' => "Nazwa użytkownika nie może zawierać znaków specjalnych",
        ]);

        $this->createMessages('password', [
            'between' => "Hasło powinno zawierać od " . $this->between('password.min') . " do " . $this->between('password.max') . " znaków",
        ]);

        $this->createMessages('email', [
            'sanitize' => "Adres email zawiera niedozwolone znaki",
            'validate' => "Adres email nie jest prawidłowy",
        ]);

        $this->createMessages('avatar', [
            'maxSize' => "Przesłany plik jest zbyt duży. Rozmiar pliku nie może być większy niż " . $this->value('avatar.maxSize') . " pixelów",
            'types' => "Przesyłany plik posiada niedozwolone rozszerzenie. Dozwolone rozszeszenia to: " . $this->arrayValue('avatar.types', true),
        ]);

        $this->createMessages('regulations', [
            'require' => "Regulamin nie został zaakceptowany",
        ]);
    }
}
