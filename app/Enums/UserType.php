<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

final class UserType extends Enum
{
      const VENDOR = 1;
      const BUYER = 2;

      public static function getTypes(): array
      {
            return [
                  self::VENDOR => 'Vendor',
                  self::BUYER => 'Buyer',
            ];
      }

      public static function getTypeName(int $type): string
      {
            return self::getTypes()[$type];
      }
}
