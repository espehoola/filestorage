<?php
declare(strict_types=1);

namespace Docs\Api;

interface HealthCheckControllerInterface
{
    /**
     * @OA\Get(
     *     path="/health-check",
     *     summary="Checks that the service is working correctly",
     *     description="Checks that the service is working correctly",
     *     @OA\Response(
     *         response=200,
     *         description="List of statuses of DB connection and file system",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(
     *                         property="Fylesystem",
     *                         type="string",
     *                         description="Файловая система",
     *                         example="success",
     *                     ),
     *                     @OA\Property(
     *                         property="Databse",
     *                         type="string",
     *                         description="Соединение с БД",
     *                         example="fail",
     *                     ),
     *                 ),
     *             ),
     *         ),
     *     ),
     * )
     */
    public function healthCheck(): array;
}
