<?php
declare(strict_types=1);

namespace App\Services;

use App\Exceptions\FileStorageDirectoryCapacityReachedException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Сервис для работы с директориями
 */
class DirectoryService
{
    public const FIRST_DIRECTORY = 'AAA';
    public const FINALLY_DIRECTORY = 'AAB';

    /**
     * Генерирует и создает, если это необходимо, путь до директории, куда будет сохраняться файл
     * @return string
     */
    public function generatePath(): string
    {
        $directoryOfFirstLevel = $this->getNodeByPath('');
        $directoryOfSecondLevel = $this->getNodeByPath($directoryOfFirstLevel);
        $pathToUploadDir = $directoryOfFirstLevel . DIRECTORY_SEPARATOR . $directoryOfSecondLevel;

        // превышен лимит по файлам директории второго уровня
        if ($this->isCountNodesPeaked($pathToUploadDir)) {
            try {
                $directoryOfSecondLevel = $this->increment($directoryOfSecondLevel);
            } catch (FileStorageDirectoryCapacityReachedException $exception) {
                // превышен лимит по директориям для директории второго уровня
                // пробуем сделать инкремент для директории первого уровня
                try {
                    $directoryOfFirstLevel = $this->increment($directoryOfFirstLevel);
                    $directoryOfSecondLevel = self::FIRST_DIRECTORY;
                } catch (FileStorageDirectoryCapacityReachedException $exception) {
                    Log::error($exception::class . ': достигнут предел структуры директорий');
                }
            }

            $pathToUploadDir = $directoryOfFirstLevel . DIRECTORY_SEPARATOR . $directoryOfSecondLevel;

            Storage::createDirectory($pathToUploadDir);
        }

        return $pathToUploadDir;
    }

    /**
     * Получить ноды в текущей директории
     * @param string $path
     * @return array
     */
    public function getNodes(string $path): array
    {
        return array_diff(
            scandir(Storage::path($path)),
            ['.', '..'],
        );
    }

    /**
     * Получить имя крайней директории
     * @param string $path
     * @return string|null
     */
    public function getFolderName(string $path): ?string
    {
        $nodes = $this->getNodes($path);

        return !empty($nodes) ? max($nodes) : self::FIRST_DIRECTORY;
    }

    /**
     * Достигнут предел по файлам в директории
     * @param string $path
     * @return bool
     */
    public function isCountNodesPeaked(string $path): bool
    {
        return count(Storage::files($path)) >= config('app.settings.max_files_in_folder');
    }

    /**
     * Инкремент наименования директории
     * @param string $folderName
     * @return string
     * @throws FileStorageDirectoryCapacityReachedException
     */
    public function increment(string $folderName): string
    {
        if ($folderName === self::FINALLY_DIRECTORY) {
            throw new FileStorageDirectoryCapacityReachedException();
        }

        return str_increment($folderName);
    }

    /**
     * Получить имя директории для пути
     * @param string $path
     * @return string|null
     */
    public function getNodeByPath(string $path): ?string
    {
        $nodes = $this->getNodes($path);
        if (empty($nodes)) {
            $directoryName = self::FIRST_DIRECTORY;
            Storage::makeDirectory($path . DIRECTORY_SEPARATOR . $directoryName);

            return $directoryName;
        }

        return $this->getFolderName($path);
    }
}
