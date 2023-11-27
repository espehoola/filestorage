<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\HealthCheckStatusEnum;
use App\Models\File;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Сервис для проверки работоспособности
 */
class HealthCheckService
{
    public function __construct(
        protected FileService $fileService,
        protected DirectoryService $directoryService,
    ) {}

    /**
     * Проверка состояния файловой системы
     * @return HealthCheckStatusEnum
     */
    public function checkFileSystem(): HealthCheckStatusEnum
    {
        $status = false;

        try {
            $status = is_writable(Storage::path($this->directoryService->generatePath()));
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }

        return $this->getStatusCode($status);
    }

    /**
     * Проверка работоспособности базы данных
     * @return HealthCheckStatusEnum
     */
    public function checkDatabase(): HealthCheckStatusEnum
    {
        $fileTable = (new File())->getTable();
        $isFileTableExist = Schema::hasTable($fileTable);

        return $this->getStatusCode($isFileTableExist);
    }

    /**
     * Вернуть код сообщения
     * @param bool $isHealthy
     * @return HealthCheckStatusEnum
     */
    public function getStatusCode(bool $isHealthy): HealthCheckStatusEnum
    {
        return $isHealthy ? HealthCheckStatusEnum::SUCCESS : HealthCheckStatusEnum::FAIL;
    }
}
