<?php
declare(strict_types = 1);

namespace Docs\Api;

use App\Http\Requests\FileCreateRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface FileInterface
{
    /**
     * @OA\Post(
     *     path="/file",
     *     summary="Uploads file and persists file info to DB",
     *     description="Uploads file",
     *     operationId="createFile",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/File",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation failed",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     * )
     */
    public function create(FileCreateRequest $request): JsonResponse;

    /**
     * @OA\Get(
     *     path="/file/{uuid}/info",
     *     summary="Gets file's info by Uuid",
     *     description="Gets file's info by Uuid",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="Uuid of file",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="ce1210ee-b240-47f3-a052-4aa1e75a8090",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 ref="#/components/schemas/File",
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found Error",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     * )
     */
    public function getByUuid(string $uuid): JsonResponse;

    /**
     * @OA\Delete(
     *     path="/file/{uuid}",
     *     summary="Deletes a file by Uuid",
     *     description="Deletes a file by Uuid",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="Uuid of file",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="ce1210ee-b240-47f3-a052-4aa1e75a8090",
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found Error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     * )
     */
    public function delete(string $uuid): JsonResponse;

    /**
     * @OA\Get(
     *     path="/file/{uuid}",
     *     summary="Returns file to download",
     *     description="Returns file to download",
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="Uuid of file",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="ce1210ee-b240-47f3-a052-4aa1e75a8090",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ok",
     *         @OA\Schema(type="file"),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found Error",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *         ),
     *     ),
     * )
     */
    public function downloadFile(string $uuid): BinaryFileResponse;
}
