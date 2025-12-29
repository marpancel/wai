<?php

class User
{
    public string $email;
    public string $login;
    public string $password;
    public string $profilePhoto;

    public function __construct($email, $login, $password, $profilePhoto)
    {
        $this->email = $email;
        $this->login = $login;
        $this->password = $password;
        $this->profilePhoto = $profilePhoto;
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'login' => $this->login,
            'password' => $this->password,
            'profile_photo' => $this->profilePhoto,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];
    }
}