<?php

declare(strict_types=1);

namespace common\helpers;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper as YiiFileHelper;

/**
 * Class FileHelper
 *
 * Для работы с файлами общий хелпер
 */
class FileHelper extends YiiFileHelper
{
    /** @var int Максимальное количество попыток сгенерировать уникальное название файла */
    private const MAX_ATTEMPTS = 100;

    /** @var string Название алиаса */
    private const PATH_ALIAS_NAME = '@uploads';

    /**
     * Сгенерировать уникальное название файла
     *
     * @param string $path
     * @param string $filename
     * @param string $extension
     * @return string
     * @throws \Exception
     */
    public static function uniqueFilename(string $path, string $filename, string $extension): string
    {
        if (trim($path) === '' || trim($filename) === '' || trim($extension) === '') {
            throw new \InvalidArgumentException('Path, filename, and extension must be non-empty strings');
        }

        $counter = 1;
        $originalFilename = $filename;
        while (file_exists("{$path}/{$filename}.{$extension}")) {
            if ($counter > self::MAX_ATTEMPTS) {
                throw new \Exception('Unable to generate a unique filename after ' . self::MAX_ATTEMPTS . ' attempts.');
            }

            $filename = "$originalFilename($counter)";
            $counter++;
        }

        return "{$filename}.{$extension}";
    }

    /**
     * Сохранить файл по указанному пути
     *
     * @param string $path
     * @param UploadedFile|null $uploadedFile
     * @return string|bool
     * @throws \Exception
     */
    public static function saveFile(string $path, ?UploadedFile $uploadedFile): string|bool
    {
        if (trim($path) === '') {
            throw new \InvalidArgumentException('Path must be non-empty strings');
        }

        if ($uploadedFile === null) {
            return false;
        }

        $fullPathDir = Yii::getAlias(self::PATH_ALIAS_NAME . "/$path");
        if (!is_dir($fullPathDir) || !is_readable($fullPathDir) || !is_writable($fullPathDir)) {
            parent::createDirectory($fullPathDir);
        }

        try {
            $filename = self::uniqueFilename($fullPathDir, $uploadedFile->baseName, $uploadedFile->extension);
            if (!$uploadedFile->saveAs("$fullPathDir/$filename")) {
                Yii::error("FileHelper::saveFile - Failed to save file: {$filename}", 'api');

                return false;
            }
        } catch (\Exception $e) {
            Yii::error("FileHelper::saveFile - {$e->getMessage()}", 'api');
            return false;
        }

        return "/$path/$filename";
    }

    /**
     * Удалить файл по пути
     *
     * @param string $fullPath
     * @return bool
     */
    public static function deleteFile(string $fullPath): bool
    {
        if (trim($fullPath) === '') {
            throw new \InvalidArgumentException('Path must be non-empty strings');
        }

        $fullPathDir = Yii::getAlias(self::PATH_ALIAS_NAME . "/$fullPath");
        if (file_exists($fullPathDir)) {
            try {
                if (parent::unlink($fullPathDir)) {
                    \Yii::info("FileHelper::deleteFile - File successfully deleted: {$fullPathDir}", 'api');

                    return true;
                } else {
                    \Yii::error("FileHelper::deleteFile - Failed to delete file: {$fullPathDir}", 'api');
                }
            } catch (\Exception $e) {
                \Yii::error("FileHelper::deleteFile - Error deleting file: {$e->getMessage()}", 'api');
            }
        } else {
            \Yii::warning("FileHelper::deleteFile - File not found: {$fullPathDir}", 'api');
        }

        return false;
    }
}