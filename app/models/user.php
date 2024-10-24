<?php

class User extends Model
{
    public function check($username, $password)
    {
        $user =  $this->findFirst('WHERE username = :username', [':username' => $username]);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }
}
