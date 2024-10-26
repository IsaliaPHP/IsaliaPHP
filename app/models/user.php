<?php

/**
 * Modelo para la entidad User.
 * @author nelson rojas
 * @property password string
 */
class User extends Model
{
    public function check(string $username, string $password): bool|User
    {
        $user =  $this->findFirst('WHERE username = :username', [':username' => $username]);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }
}
