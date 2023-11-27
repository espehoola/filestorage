<?php
declare(strict_types=1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\HealthCheckService;
use Docs\Api\HealthCheckControllerInterface;

/**
 * Эндпоинты состояние системы
 */
class HealthCheckController extends Controller implements HealthCheckControllerInterface
{
    public function __construct(
        protected HealthCheckService $healthCheckService,
    ) {}

    /**
     * Проверка работоспособности микросервиса
     *
     * @return array
     */
    public function healthCheck(): array
    {
        return [
            'Filesystem' => $this->healthCheckService->checkFileSystem(),
            'Database' => $this->healthCheckService->checkDatabase(),
        ];
    }
}
