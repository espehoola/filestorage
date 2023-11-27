<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $size
 * @property string $disk
 * @property string $mime_type
 * @property string $file_hash
 * @property string $owner_ip
 * @property string $path
 * @property string $url
 * @property array $meta
 * @property string $last_access
 * @property int $readings
 * @property string $created_at
 * @property string $updated_at
 */
class File extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'disk',
        'name',
        'size',
        'mime_type',
        'file_hash',
        'path',
        'url',
        'last_access',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'last_access' => 'datetime',
        'meta' => 'array',
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Чтение файла
     * @return $this
     */
    public function touch($attribute = null): self
    {
        $this->readings = ++$this->readings;
        parent::touch('last_access');

        return $this;
    }
}
