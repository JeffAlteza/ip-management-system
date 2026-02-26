<?php

namespace App\Enums;

enum Role: string
{
    case User = 'user';
    case SuperAdmin = 'super_admin';
}
