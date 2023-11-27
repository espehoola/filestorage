<?php
declare(strict_types=1);

namespace App\Enums;

/**
 * Перечисления статусов проверки состояния системы
 */
enum HealthCheckStatusEnum: string
{
    case SUCCESS = 'success';
    case FAIL = 'fail';
}
