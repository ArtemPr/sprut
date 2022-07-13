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

            /*
             * @var $checkedFile array будет доступна из класса, который захочет использовать этот трейт
             */
            if ($file->move($currSavePlace, $originalName)) {
                $this->checkedFile = pathinfo($currSavePlace.'/'.$originalName);

                if ('array' != gettype($this->checkedFile)) {
                    $this->checkedFile = [
                        'pathinfo' => $this->checkedFile,
                    ];
                }

                $this->checkedFile['path'] = $currDownloadPlace.'/'.$originalName;
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

    /**
     * Предполагаемый обработчик сохранения файла в локальном хранилище.
     */
    protected function downloadFile(string $remoteFile, string $moduleName, int $docId): string|null
    {
        if (str_contains($moduleName, '\\')) {
            $moduleName = strtolower(substr($moduleName, strrpos($moduleName, '\\') + 1));
        }

        $currSavePlace = $_SERVER['DOCUMENT_ROOT'].'uplfile/'.$moduleName;
        $currDownloadPlace = '/uplfile/'.$moduleName;
        $currFileName = 'Report_'.$docId.'_'.date('Ymd').'.pdf';

        if (false !== file_put_contents($currSavePlace.'/'.$currFileName, $remoteFile)) {
            return $currDownloadPlace.'/'.$currFileName;
        }

        return false;
    }
}
