<?php
/*
 * Created AptPr <prudishew@yandex.ru> 2022.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

trait LinkService
{

    public function getParinatorLink()
    {
        $query_data = $this->getLinkParam();
        if (!empty($query_data['page'])) {
            unset($query_data['page']);
        }
        return $this->createLinkString($query_data) . 'page=';
    }

    public function getSortLink()
    {
        $query_data = $this->getLinkParam();
        if (!empty($query_data['sort'])) {
            unset($query_data['sort']);
        }
        if (!empty($query_data['page'])) {
            unset($query_data['page']);
        }
        return $this->createLinkString($query_data) . 'sort=';
    }

    private function createLinkString(array $query_data)
    {
        $out = "";
        foreach ($query_data as $key => $value) {
            $out .= $key . '=' . $value . '&';
        }
        return strlen($out) > 0 ? '?' . $out : '?' ;
    }

    private function getLinkParam()
    {
        $request = new Request($_GET);
        return $request->query->all();

    }
}
