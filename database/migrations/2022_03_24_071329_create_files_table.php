<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('disk');
            $table->string('name')->comment('Имя файла и расширение');
            $table->string('size')->comment('Размер в байтах');
            $table->string('mime_type');
            $table->string('file_hash');
            $table->ipAddress('owner_ip')->comment('IP, с которого загружен файл');
            $table->string('path');
            $table->string('url');
            $table->jsonb('meta')->nullable();
            $table->dateTime('last_access')->nullable();
            $table->unsignedBigInteger('readings')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
