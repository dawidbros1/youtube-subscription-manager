<?php

declare (strict_types = 1);

namespace App\Model;

use Phantom\Helper\Session;
use Phantom\Model\AbstractModel;

class PasswordRecovery extends AbstractModel
{
    # Method sets new password for user
    public function resetPassword($data, $code)
    {
        if ($status = $this->validate($data)) {
            $user = $this->find(['email' => Session::get($code)], "", true, User::class);
            $user->set('password', $data['password']);
            $user->hashPassword();
            $user->update([], false);

            Session::clearArray([$code, "created:" . $code]);
            Session::success('Hasło do konta zostało zmienione');
        }
        return $user ?? null;
    }

    # Method checks if email exists and return status
    public function existsEmail($email)
    {
        return in_array($email, $this->repository->getEmails());
    }
}
