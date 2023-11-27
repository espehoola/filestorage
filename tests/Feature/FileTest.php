<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class FileTest extends TestCase
{
    public static string $url = 'api/v1/file';

    /**
     * A basic test example.
     *
     * @return void
     * @throws Exception
     */
    public function testNewFolderCreation()
    {
        $file = UploadedFile::fake()->image('image.png', random_int(1, 1000000));
        $response = $this->post(self::$url, compact('file'));

        $response->assertSuccessful();
    }
}
