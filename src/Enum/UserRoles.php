<?php

namespace App\Enum;

 enum UserRoles: string
{
    case ROLE_USER = 'ROLE_USER';
    case ROLE_ADMIN = 'ROLE_ADMIN';

     /**
      * @return UserRoles[]
      */
    public static function getChoices(): array
    {
        return [
            'User' => self::ROLE_USER->value,
            'Super Admin' => self::ROLE_ADMIN->value,
        ];
    }

    public static function getLabel(string $role): string
    {
        $choices = self::getChoices();
        $choices = array_flip($choices);

        return $choices[$role] ?? '';
    }
}
