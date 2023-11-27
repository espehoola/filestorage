<?php
declare(strict_types = 1);

namespace App\Services;

use App\Exceptions\FileStorageException;
use App\Models\File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Сервис по работе с файлами
 */
class FileService
{
    public function __construct(
        protected DirectoryService $directoryService
    ) {}

    /**
     * @var Request
     */
    private Request $request;

    /**
     * Устанавливает экземпляр Http-запроса
     *
     * @param Request $request
     * @return $this
     */
    public function setRequest(Request $request): self
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Получить модель File по uuid
     *
     * @param string $uuid
     * @return File
     */
    public function getFileByUuid(string $uuid): File
    {
        return File::whereUuid($uuid)->firstOrFail();
    }

    /**
     * Сохраняется переданный в запросе файл на диске и сохраняет запись о нем в БД
     *
     * @return File
     * @throws Exception
     */
    public function uploadFileAndSaveDataToDatabase(): File
    {
        $file = new File();
        $file->uuid = Str::uuid()->toString();

        $relativePathToFileUploadFolder = $this->directoryService->generatePath();
        $absolutePathToFileUploadFolder = Storage::path($relativePathToFileUploadFolder);

        /** @var UploadedFile $fileToUpload */
        $fileToUpload = $this->request->file('file');
        $fileHash = hash_file('md5', $fileToUpload->getRealPath());

        /** @var File $fileWithSameHash */
        $fileWithSameHash = File::where('file_hash', $fileHash)->first();
        if ($fileWithSameHash !== null) {
            $file->name = $fileWithSameHash->name;
            $file->size = $fileWithSameHash->size;
            $file->mime_type = $fileWithSameHash->mime_type;
            $file->file_hash = $fileWithSameHash->file_hash;
            $file->path = $fileWithSameHash->path;
            $file->meta = $fileWithSameHash->meta;
            $file->owner_ip = $this->request->getClientIp();
            $file->disk = $fileWithSameHash->disk;
        } else {
            $uploadedFile = $fileToUpload->move(
                $absolutePathToFileUploadFolder,
                "$fileHash." . $fileToUpload->extension()
            );

            $file->name = $fileHash . '.' . $uploadedFile->getExtension();
            $file->size = (string)$uploadedFile->getSize();
            $file->mime_type = $uploadedFile->getMimeType();
            $file->file_hash = $fileHash;
            $file->owner_ip = $this->request->getClientIp();
            $file->path = $relativePathToFileUploadFolder . DIRECTORY_SEPARATOR . $file->name;
            [$width, $height] = getimagesize(Storage::path($file->path));
            if (!empty($width) && !empty($height)) {
                $file->meta = ['width' => $width, 'height' => $height];
            }
            $file->disk = config('filesystems.default');
        }
        $file->url = route('file.get', ['uuid' => $file->uuid]);

        if (!$file->save()) {
            unlink(Storage::path($file->path));
            throw new FileStorageException('Failed to save to database');
        }

        return $file;
    }

    /**
     * Скачать файл
     * @param File $file
     * @return string
     */
    public function download(File $file): string
    {
        $file->touch();
        return $this->getFullPath($file);
    }

    /**
     * Получить полный путь к файлу
     * @param File $file
     * @return string
     */
    public function getFullPath(File $file): string
    {
        return Storage::disk($file->disk)->path($file->path);
    }

    /**
     * Удаляет файл по uuid
     *
     * @param string $uuid
     * @return bool
     * @throws FileStorageException
     */
    public function deleteFileByUuid(string $uuid): bool
    {
        /** @var File $file */
        $file = File::whereUuid($uuid)->first();

        if (!$file) {
            throw new NotFoundHttpException();
        }

        $filesWithSameHashCount = File::where('file_hash', $file->hash)->count();

        if ($filesWithSameHashCount > 1) {
            return $file->delete();
        }

        return $this->deleteWithFile($file);
    }

    /**
     * Удаляет запись в БД, предварительно удаляя файл с диска
     * @param File $file
     * @return bool
     * @throws FileStorageException
     */
    public function deleteWithFile(File $file): bool
    {
        DB::beginTransaction();
        try {
            Storage::disk($file->disk)->delete($file->path);
            $file->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            throw new FileStorageException('Cannot delete file from disc');
        }
        return true;
    }
}
