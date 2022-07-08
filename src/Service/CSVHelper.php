<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

trait CSVHelper
{
    public function getCSVFile(string $table, string $filename): Response
    {
        $table = mb_convert_encoding($table, 'UTF-16LE', 'UTF-8');
        $table = htmlspecialchars_decode($table);

        $response = new Response($table);

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Description', 'File Transfer');
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Expires', '0');
        $response->headers->set('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Content-Length', '' . strlen($table));

//        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
//        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
