<?php

namespace Perspective\Matomo\Helper;

use Exception;

class DealId
{
    /**
     * In client code you need to check for integer.
     * Only integer is valid deal to show
     * @param $url
     * @return int|mixed
     */
    public function process($url)
    {
        if (strpos($url, 'catalog/product/view') !== false) {
            $preparedUrl = rtrim($url, '/') . '/';
            $re = '/.*\/id\/(.*?)\//m';
            preg_match_all($re, $preparedUrl, $matches);
            try {
                return (int)$matches[1][0] ?? $url;
            } catch (Exception $e) {
                return $url;
            }
        } else {
            return $url;
        }
    }
}
