<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait CSVHelper
{
    /**
     * Экспорт встроенными средствами языка (теперь работает и в вендоекселе, и в Linux и в MacOS).
     */
    public function processCSV(array $data, string $filename)
    {
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename='.$filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        $df = fopen('php://output', 'w');

        // This line is important:
        fputs($df, "\xEF\xBB\xBF"); // UTF-8 BOM !!!!!

        foreach ($data as $row) {
            fputcsv($df, $row, ';');
        }
        fclose($df);
        exit();
    }

    /**
     * Экспорт в csv через PhpSpreadsheet (на выходе windows-1251 и норм работа в вендоекселе, но не в Linux и не в MacOS).
     */
    public function getCSVFile(array $data, string $filename): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $rowNumber = 1;

        foreach ($data as $dataRow) {
            $columnLetter = 'A';

            foreach ($dataRow as $value) {
                $sheet->setCellValue($columnLetter.$rowNumber, $value);
                ++$columnLetter;
            }

            ++$rowNumber;
        }

        $contentType = 'text/csv';
        $writer = new Csv($spreadsheet);
        $writer->setOutputEncoding('windows-1251');
        $writer->setDelimiter(';');
        $writer->setExcelCompatibility(false);
        $writer->setUseBOM(false);

        $response = new StreamedResponse();
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setCallback(function () use ($writer) {
            $writer->save('php://output');
        });

        return $response;
    }
}
