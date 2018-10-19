<?php

namespace Lib;

/**
 * Class that contains functions that use CURL requests
 * @package lib
 */
class CCurl
{

    /**
     * This function performs a standard CURL request, customizable with curl options and request types
     *
     * @param string $url         the request URL
     * @param array  $options     array of options, to be passed to curl_setopt
     * @param string $requestType CURL request type (eg. POST, PUT). Default CURL request type is GET
     * @param array  $data        request data
     *
     * @return bool|mixed returns the response if it exists. If response is empty, it returns false
     */
    function callAPI($url, $options = [], $requestType = null, $data = [])
    {
        $response = [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        foreach ($options as $curlOption => $curlOptionValue) {
            curl_setopt($ch, $curlOption, $curlOptionValue);
        }
        if (isset($requestType)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestType);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_exec($ch);

        $response['Code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response['Body'] = curl_exec($ch);

        curl_close($ch);

        if (!empty($response)) {
            return $response;
        } else {
            return false;
        }
    }

    /**
     * Check HTTP status for a page
     *
     * @param string $page page url
     *
     * @return boolean true if page is up or false if page has internal issues
     */
    function checkPageStatus($page)
    {

        $ch = curl_init($page);

        //proxy settings
        $proxy = 'http://proxy.avangate.local:8080';
        if (strpos($page, '.local') == false) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }

        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode >= 200 && $httpcode < 300) {
            return true;
        } else {
            return false;
        }
    }

}
