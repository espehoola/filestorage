<?php
declare(strict_types = 1);

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileCreateRequest;
use App\Services\FileService;
use Docs\Api\FileInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Эндпоинты работы с файлами
 */
class FileController extends Controller implements FileInterface
{
    /**
     * @param FileService $fileService
     */
    public function __construct(
        public FileService $fileService,
    ) {}

    /**
     * Отдает файл для скачивания по указанному uuid
     *
     * @param string $uuid
     * @return BinaryFileResponse
     */
    public function downloadFile(string $uuid): BinaryFileResponse
    {
        $file = $this->fileService->getFileByUuid($uuid);

        $headers = config('app.settings.downloaded_file_headers');

        return response()->file($this->fileService->download($file), $headers);
    }

    /**
     * Сохраняет загружаемый файл и делает запись в БД
     *
     * @param FileCreateRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function create(FileCreateRequest $request): JsonResponse
    {
        return response()->json(
            $this->fileService->setRequest($request)->uploadFileAndSaveDataToDatabase(),
            201
        );
    }

    /**
     * Отдает информацию о файле по его uuid
     *
     * @param string $uuid
     * @return JsonResponse
     */
    public function getByUuid(string $uuid): JsonResponse
    {
        return response()->json(
            $this->fileService->getFileByUuid($uuid),
        );
    }

    /**
     * Удаляет запись о файле по uuid и удаляет сам файл
     *
     * @param string $uuid
     * @return JsonResponse
     * @throws Exception
     */
    public function delete(string $uuid): JsonResponse
    {
        $this->fileService->deleteFileByUuid($uuid);
        return response()->json([], 204);
    }
}
