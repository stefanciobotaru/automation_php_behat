<?php

namespace Asserts;

use GitHubContext;

class Asserts
{

    /**
     * Asserts response code 201 Created for add repo
     *
     * @param $responseCode
     *
     * @throws \Exception
     */
    public static function assertRepoIsCreated($responseCode)
    {
        if ($responseCode != 201) {
            throw new \Exception("Add new repo call replied with: $responseCode, different than 201");
        }

    }

    /**
     * Asserts response code 204 No Content for delete repo with success
     *
     * @param $responseCode
     *
     * @throws \Exception
     */
    public static function assertRepoIsDeletedWithSuccess($responseCode)
    {
        if ($responseCode != 204) {
            throw new \Exception("Delete repo call replied with: $responseCode, different than 204");
        }

    }

    /**
     * Asserts response code 404 Not Found for delete repo that fails
     *
     * @param $responseCode
     *
     * @throws \Exception
     */
    public static function assertRepoDeleteThatFails($responseCode)
    {
        if ($responseCode != 404) {
            throw new \Exception("Delete fail repo call replied with: $responseCode, different than 404");
        }

    }

    /**
     * Asserts the number of remaining repos
     *
     * @param $expected
     * @param $actual
     *
     * @throws \Exception
     */
    public static function assertRemainingRepos($expected, $actual)
    {

        $count = 0;

        if (is_array($actual)) {
            foreach ($actual as $repo) {
                if ($repo->name != GitHubContext::MAIN_REPO) {
                    $count++;
                }
            }
        } else {
            throw new \Exception('No available repos');
        }


        if ($count != $expected) {
            throw new \Exception("The expected number of remaining repositories: $count is not equal to expected: $expected");
        }
    }


    /**
     * Asserts repo is present
     *
     * @param $expected
     * @param $actual
     *
     * @throws \Exception
     */
    public static function assertRepoIsPresent($expected, $actual)
    {
        if (is_array($actual)) {
            foreach ($actual as $repo) {
                if ($repo->name != $actual) {
                    throw new \Exception("The expected repo: $expected is not present");
                }
            }
        } else {
            throw new \Exception('No available repos to compare');
        }
    }
}