<?php

namespace Lib;

use Data\Data;

/**
 * Class that initializes services and authentication required REST API
 * @package lib
 */
class CAPI
{

    protected $user;
    protected $password;
    protected $token;

    public $curlClass = null;

    /**
     * This function initializes the cURL service and gets authorization token for GitHub API services (public_repo and delete_repo)
     *
     * @return array returns call response code and token details
     * @throws \Exception
     */
    public function getAuthToken()
    {

        $this->curlClass = new CCurl();

        $curlOptions = $this->getCurlOptionsForBasicAuth();

        $note = 'auth' . Data::generateRandomString(10);

        $requestData = "{\r\n  \"scopes\": [\r\n    \"repo\",\r\n    \"delete_repo\"\r\n  ],\r\n  \"note\": \"$note\"\r\n}";

        $response = $this->curlClass->callAPI('https://api.github.com/authorizations', $curlOptions, 'POST',
            $requestData);

        return $this->decodeResponse($response);
    }

    /**
     * This function initializes the cURL service and deletes authorization token for GitHub API services
     *
     * @param integer $id Auth token id
     *
     * @return array returns call response code and token details
     * @throws \Exception
     */
    public function deleteAuthToken($id)
    {

        $this->curlClass = new CCurl();

        $curlOptions = $this->getCurlOptionsForBasicAuth();

        $response = $this->curlClass->callAPI('https://api.github.com/authorizations/' . $id, $curlOptions, 'DELETE');

        return $this->decodeResponse($response);
    }

    /**
     * This function initializes the cURL service and authenticates in GitHub API with OAuth
     *
     * @param string $hostUrl
     * @param string $method
     * @param string $requestData
     * @param bool   $debug
     *
     * @return array returns call response code and body
     * @throws \Exception
     */
    public function callGitHubAPIOAuth($hostUrl, $method, $requestData = null, $debug = false)
    {

        $this->curlClass = new CCurl();

        $curlOptions = $this->getCurlOptionsForOAuth();

        if ($debug) {
            echo "Request: " . $requestData . "\n";
            echo "URL: " . $hostUrl . "\n";
            echo "Headers:" . print_r($curlOptions);
        }

        $response = $this->curlClass->callAPI($hostUrl, $curlOptions, $method, $requestData);

        if ($debug) {
            print_r($response);
            ob_flush();
        }

        return $this->decodeResponse($response);
    }

    /**
     * Gets curl options for Basic Auth
     *
     * @return array
     */
    public function getCurlOptionsForBasicAuth()
    {

        $curlOptions = [
            CURLOPT_USERPWD    => $this->user . ":" . $this->password,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/vnd.github.v3+json',
                'User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/537.36',
            ],
        ];

        return $curlOptions;
    }

    /**
     * Gets curl options for OAuth
     *
     * @return array
     */
    public function getCurlOptionsForOAuth()
    {

        $curlOptions = [
            CURLOPT_HTTPHEADER => [
                'Authorization: token ' . $this->token,
                'Content-Type: application/json',
                'Accept: application/vnd.github.v3+json',
                'User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 YaBrowser/16.3.0.7146 Yowser/2.5 Safari/537.36',
            ],
        ];
        return $curlOptions;
    }

    /**
     * Decodes response from json format to array
     *
     * @param $response
     *
     * @return mixed
     * @throws \Exception
     */
    public function decodeResponse($response)
    {
        if (!empty($response)) {

            $responseCode = json_decode($response['Code']);
            $responseBody = json_decode($response['Body']);

            $response['Code'] = $responseCode;
            $response['Body'] = $responseBody;

            return $response;
        } else {
            throw new \Exception("No response");
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

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

}
