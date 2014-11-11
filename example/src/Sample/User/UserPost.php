<?php
namespace Sample\User;

class UserPost
{
    private $email;
    private $password;
    private $passwordRepeat;

    public function __construct($request)
    {
        $this->email = $request->get('email');
        $this->password = $request->get('password');
        $this->passwordRepeat = $request->get('passwordRepeat');
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getPasswordRepeat()
    {
        return $this->passwordRepeat;
    }
}