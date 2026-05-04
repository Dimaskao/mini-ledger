<?php

namespace App\Enum;

enum NotificationChannel: string
{
    case Email = 'email';
    case Telegram = 'telegram';
}
