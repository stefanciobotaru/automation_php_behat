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
     * @param string $requestType CURL request type (eg. GET, POST, PUT)
     * @param array  $data        request data
     *
     * @return array returns the response and response code
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

}
