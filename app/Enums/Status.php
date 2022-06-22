<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

final class Status extends Enum
{
      const PENDING = 0;
      const DILIVERED = 1;

      public static function getTypes(): array
      {
            return [
                  self::DILIVERED => 'Dilivered',
                  self::PENDING => 'Pending',
            ];
      }

      public static function getTypeName(int $type): string
      {
            return self::getTypes()[$type];
      }
}
