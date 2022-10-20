<?php

declare (strict_types = 1);

namespace App\Model;

use Phantom\Helper\Session;
use Phantom\Model\AbstractModel;
use Phantom\Model\Config;

class User extends AbstractModel
{
    public $id, $username, $email, $password, $avatar, $role, $created;
    public static $defaultAvatar;
    public static $uploadedLocation;
    public static function initConfiguration(Config $config)
    {
        self::$defaultAvatar = $config->get('default.path.avatar');
        self::$uploadedLocation = $config->get('upload.path.avatar');
    }
    public $fillable = ['id', 'username', 'email', 'password', 'avatar', 'role', 'created'];

    # Method logouts user by unsets session user:id
    public function logout()
    {
        Session::clear('user:id');
        Session::success("Nastąpiło wylogowanie z systemu");
    }

    # Method updates username
    public function updateUsername()
    {
        if ($this->update(['username'])) {
            Session::success("Nazwa użytkownika została zmieniona");
        }
    }

    # Method updates password
    public function updatePassword($data)
    {
        # Checks if currunt password is corrent
        if (!$same = ($this->password == $this->hash($data['current_password']))) {
            Session::set("error:current_password:same", "Podane hasło jest nieprawidłowe");
        }

        # Validate password and repeat_password
        if ($this->validate($data) && $same) {
            $this->set('password', $data['password']);
            $this->hashPassword();

            if ($this->update([], false)) {
                Session::success('Hasło zostało zaktualizowane');
            }
        }
    }

    # Method updates avatar
    public function updateAvatar($file, $path)
    {
        # Validate the image with the size and extension
        if ($this->validateImage($file, 'avatar')) {
            $file = $this->hashFile($file); # Change file name to unique file name

            # Upload file to selected path from config
            if ($this->uploadFile($path, $file)) {
                $this->deleteAvatar(); # Delete old avatar
                $this->set('avatar', $file['name']);

                # Update path to avatar in database
                if ($this->update([], false)) {
                    Session::success('Awatar został zaktualizowany');
                }
            }
        }
    }

    # Method checks if user role is admin
    public function isAdmin()
    {
        return (bool) ($this->role === "admin");
    }

    // ===== ===== ===== ===== =====

    # Method deletes avatar
    public function deleteAvatar()
    {
        if ($this->avatar != null && file_exists($this->getAvatar())) {
            unlink($this->getAvatar());
        }
    }

    # Method gets avatar
    public function getAvatar(bool $toView = false)
    {
        if ($this->avatar == null) {
            # if avatar is not sets -> get path to default avatar
            $avatar = $this->avatar ?? self::$defaultAvatar;
        } else {
            # if avatar is set -> get path to uploadedLocation avatar
            $avatar = self::$uploadedLocation . $this->avatar;
        }

        if ($toView === true) {
            $avatar = $this->getLocation() . $avatar;
        }

        return $avatar;
    }

    # Method hash password
    public function hashPassword()
    {
        $this->password = $this->hash($this->password);
    }

    # Method returns id of logged user
    public static function ID()
    {
        return Session::get('user:id', 0);
    }
}
