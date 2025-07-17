<?php
// app/Model/Entity/UserRole.php
declare(strict_types=1);
namespace App\Model\Entity;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}