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
        $table = mb_convert_encoding($table, 'utf8');
        $table = htmlspecialchars_decode($table);

        $response = new Response($table);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
