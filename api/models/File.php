<?php

declare(strict_types=1);

namespace api\models;

use common\helpers\FileHelper;
use yii\db\StaleObjectException;
use yii\web\UploadedFile;

/**
 * Class File
 */
class File extends \common\models\File
{
    /**
     * Получить файлы сущности
     *
     * @return array
     */
    public function getFilesByEntity(): array
    {
        return match ($this->entity) {
            parent::USER_FILE => static::find()->where(['entity' => $this->entity])->all(),
        };
    }

    /**
     * Сохранить файл на диске и создать запись
     *
     * @param string $path
     * @param int $entity
     * @param int $entity_id
     * @param UploadedFile|null $uploadedFile
     * @return bool
     * @throws \Exception
     */
    public function createFile(string $path, int $entity, int $entity_id, ?UploadedFile $uploadedFile): bool
    {
        if ($path = FileHelper::saveFile($path, $uploadedFile)) {
            $model = new self();
            $model->user_id = User::getCurrent()?->id;
            $model->entity = $entity;
            $model->entity_id = $entity_id;
            $model->path = $path;
            $model->created_at = time();

            return $model->save();
        }

        return false;
    }

    /**
     * Удаление файла физически и записи из БД
     *
     * @return int|bool
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function deleteFile(): int|bool
    {
        if (FileHelper::deleteFile($this->path)) {
            return $this->delete();
        }

        return false;
    }
}