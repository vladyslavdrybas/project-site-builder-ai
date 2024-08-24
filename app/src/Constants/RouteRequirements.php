<?php
declare(strict_types=1);

namespace App\Constants;

enum RouteRequirements: string
{
    case UUID = '^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$';
    case UNIQUE_ID = '^[a-z0-9]{13,14}\.[a-z0-9]{6}$';
    case USER_ALIAS = '^[a-z0-9\.]{3,21}$';
    case USER_SHA256 = '^[a-z0-9]{64}$';
}
