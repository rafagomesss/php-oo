<?php
namespace Code\Authenticator;

use Code\Entity\User;
use Code\Security\PasswordHash;
use Code\Session\Session;

class Authenticator
{
    private $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function login(array $credentials)
    {
        $user = current($this->user->where([
            'email' => $credentials['email']
        ]));

        if (!$user) {
            return false;
        }

        if (!PasswordHash::check($credentials['password'], $user['password'])) {
            return false;
        }

        unset($user['password']);
        Session::add('user', $user);

        return true;
    }

    public function logout()
    {
        Session::destroy();
    }
}
