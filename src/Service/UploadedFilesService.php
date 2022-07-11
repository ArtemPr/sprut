<?php
/*
 * Created admhome <admhome@ya.ru> 2022.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadedFilesService
{
    /**
     * Предполагаемый обработчик и загрузчик файлов для различных модулей.
     * @param string $fileFieldName
     * @param string $moduleName
     * @return string|null
     */
    protected function uploadFile(string $fileFieldName, string $moduleName): string|null
    {
        if (!empty($_FILES[$fileFieldName]) && \UPLOAD_ERR_OK == $_FILES[$fileFieldName]['error']) {
            $file = new UploadedFile(
                $_FILES[$fileFieldName]['tmp_name'],
                $_FILES[$fileFieldName]['name'],
                $_FILES[$fileFieldName]['type'],
                $_FILES[$fileFieldName]['error'],
            );

            $originalName = $_FILES[$fileFieldName]['name'];

            if (str_contains($moduleName, '\\')) {
                $moduleName = strtolower(substr($moduleName, strrpos($moduleName, '\\') + 1));
            }

            $currSavePlace = $_SERVER['DOCUMENT_ROOT'].'uplfile/'.$moduleName;
            $currDownloadPlace = '/uplfile/'.$moduleName;

            // if exists
            if (file_exists($currSavePlace.'/'.$originalName)) {
                $originalBaseName = substr($originalName, 0, strrpos($originalName, '.'));
                $originalExtName = substr($originalName, strrpos($originalName, '.') + 1);
                $originalName = uniqid($originalBaseName.'_').'.'.$originalExtName;
            }

            if ($file->move($currSavePlace, $originalName)) {
                $this->checkedFile = pathinfo($currSavePlace.'/'.$originalName);
                $this->checkedFile['filesize'] = filesize($currSavePlace.'/'.$originalName);
                $this->checkedFile['filetype'] = filetype($currSavePlace.'/'.$originalName);
                $this->checkedFile['mime'] = mime_content_type($currSavePlace.'/'.$originalName);

                return $currDownloadPlace.'/'.$originalName;
            } else {
                return null;
            }
        }

        return null;
    }
}
