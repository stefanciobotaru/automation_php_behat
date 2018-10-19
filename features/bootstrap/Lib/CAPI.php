<?php

namespace Lib;


/**
 * Class that initializes services and authentication required REST API
 * @package lib
 */
class CAPI
{

    protected $user;
    protected $password;

    public $curlClass = null;

    /**
     * This function initializes the cURL service and authenticates in GITHUB API
     *
     * @param string $hostUrl
     * @param string $method
     * @param string $requestData
     * @param bool   $debug
     *
     * @return array returns errors or null
     * @throws \Exception
     */
    function callGitHubAPI($hostUrl, $method, $requestData = null, $debug = false)
    {

        $this->curlClass = new CCurl();

        $curlOptions = [
            CURLOPT_USERPWD    => $this->user . ":" . $this->password,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/vnd.github.v3+json',
                'User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/537.36',
            ],
        ];

        if ($debug) {
            echo "Request: " . $requestData . "\n";
            echo "URL: " . $hostUrl . "\n";
            echo "Host URL: " . $hostUrl . "\n";
            echo "Headers:" . print_r($curlOptions);
        }

        $response = $this->curlClass->callAPI($hostUrl, $curlOptions, $method, $requestData);

        if ($debug) {
            print_r($response);
            ob_flush();
        }

        if (!empty($response)) {

            $responseCode = json_decode($response['Code']);
            $responseBody = json_decode($response['Body']);

            $response['Code'] = $responseCode;
            $response['Body'] = $responseBody;

            return $response;
        }
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

}
