# File-storage-microservice

### Описание:

Микросервис для загрузки и хранения файлов проекта


### Установка
```
make i
```

### Запуск
```
make up
```

### Генерация документации
```
make docs
```

### Документация Swagger

http://filestorage.local/api/v1/documentation

### Тестирование
```
make tests
```

### Настройки:

- Настройки микросервиса находятся в `config/app.php` в разделе `settings`.
- Правила валидации настраиваются через переменные окружения:
`ALLOWED_EXTENSIONS=png,jpg,jpeg,csv,txt,xlx,xls,pdf`, `MAX_UPLOAD_FILE_SIZE=10000`.
- В файле `config/filesystems.php` по ключу `disks` можно настроить точку монтирования.
