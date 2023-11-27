<?php

namespace docs\Schemas;

/**
 *  @OA\Schema(
 *     schema="File",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         example="1",
 *     ),
 *     @OA\Property(
 *         property="uuid",
 *         type="string",
 *         example="ce1210ee-b240-47f3-a052-4aa1e75a8090",
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         example="3c1060f05518408c8210b1b7606f424d.png",
 *     ),
 *     @OA\Property(
 *         property="size",
 *         type="string",
 *         example="999",
 *     ),
 *     @OA\Property(
 *         property="mime_type",
 *         type="string",
 *         example="image/png",
 *     ),
 *     @OA\Property(
 *         property="file_hash",
 *         type="string",
 *         example="3c1060f05518408c8210b1b7606f424d",
 *     ),
 *     @OA\Property(
 *         property="owner_ip",
 *         type="string",
 *         example="127.0.0.1",
 *     ),
 *     @OA\Property(
 *         property="disk",
 *         type="string",
 *         example="local-1",
 *     ),
 *     @OA\Property(
 *         property="path",
 *         type="string",
 *         example="AAB/AAB/3c1060f05518408c8210b1b7606f424d.png",
 *     ),
 *     @OA\Property(
 *         property="url",
 *         type="string",
 *         example="http://filestorage.local/file/ce1210ee-b240-47f3-a052-4aa1e75a8090",
 *     ),
 *     @OA\Property(
 *         property="last_access",
 *         type="string",
 *         example="2023-01-01 23:59:59",
 *     ),
 *     @OA\Property(
 *         property="readings",
 *         type="integer",
 *         example="1",
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         example="2023-01-01 23:59:59",
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         example="2023-01-01 23:59:59",
 *     ),
 * )
 */
interface FileSchemaInterface
{
}
