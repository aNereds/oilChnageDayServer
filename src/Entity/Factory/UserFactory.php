<?php

namespace App\Entity\Factory;

use App\Entity\User;

class UserFactory
{
    public static function create()
    {
        return new User();
    }
}
