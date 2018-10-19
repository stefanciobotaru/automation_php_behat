<?php
/**
 * Created by PhpStorm.
 * User: stefan.ciobotaru
 * Date: 10/19/2018
 * Time: 8:50 PM
 */

use Behat\Behat\Context\Context;
use Data\Data;
use Lib\CAPI;
use Asserts\Asserts;

class GitHubContext implements Context
{

    const OWNER     = 'stefanciobotaru';
    const MAIN_REPO = 'automation_php_behat';

    public $newRepoData;
    public $availableRepos;
    public $repoToDelete;
    public $responseCode;

    public $APIClass;

    public function __construct()
    {

        $this->APIClass = new CAPI();

        $this->APIClass->setUser("stefanciobotaru");
        $this->APIClass->setPassword("InFjXw30");

    }


    /**
     * @Given /^Valid data structure to add a new repository with (.*)$/
     * @param $name
     */
    public function validDataStructureToAddANewRepositoryWith($name)
    {
        $this->newRepoData = Data::generateRandomDataForNewRepository($name);
    }

    /**
     * @When /^I call the "([^"]*)" api method to add a repo$/
     * @param $host
     *
     * @throws Exception
     */
    public function iCallTheApiMethodToAddARepo($host)
    {
        $response = $this->APIClass->callGitHubAPI($host, "POST", $this->newRepoData);

        $this->responseCode = $response['Code'];
    }

    /**
     * @Then /^a new repository is created$/
     * @throws Exception
     */
    public function aNewRepositoryIsCreated()
    {
        Asserts::assertRepoIsCreated($this->responseCode);
    }

    /**
     * @Given /^I call the "([^"]*)" to get all repos$/
     * @param $host
     *
     * @throws Exception
     */
    public function iCallTheToGetAllRepos($host)
    {
        $response = $this->APIClass->callGitHubAPI($host, "GET");

        if (isset($response['Body'])) {
            $this->availableRepos = $response['Body'];
        } else {
            throw new \Exception('No available repos');
        }

    }

    /**
     * @Given /^and save one to be deleted$/
     * @throws Exception
     */
    public function andSaveOneForLater()
    {

        if (is_array($this->availableRepos)) {
            foreach ($this->availableRepos as $repo) {
                if ($repo->name != self::MAIN_REPO) {
                    $this->repoToDelete = $repo->name;
                    break;
                }
            }
        } else {
            throw new \Exception('No available repos');
        }
    }

    /**
     * @When /^I call the "([^"]*)" api method to delete the saved repo$/
     * @param $host
     *
     * @throws Exception
     */
    public function iCallTheApiMethodToDeleteARepo($host)
    {
        $response = $this->APIClass->callGitHubAPI($host . "/" . self::OWNER . "/$this->repoToDelete", "DELETE");

        $this->responseCode = $response['Code'];
    }

    /**
     * @Then /^the selected repo is successfully deleted$/
     * @throws Exception
     */
    public function theSelectedRepoIsSuccessfullyDeleted()
    {
        Asserts::assertRepoIsDeletedWithSuccess($this->responseCode);
    }

    /**
     * @Given /^a dummy repo to delete is saved$/
     */
    public function aDummyRepoName()
    {
        $this->repoToDelete = 'dummyRepo';
    }

    /**
     * @Then /^"([^"]*)" remaining repositories are still present$/
     * @param $expected
     *
     * @throws Exception
     */
    public function remainingRepositoriesAreStillPresent($expected)
    {
        Asserts::assertRemainingRepos($expected, $this->availableRepos);
    }


    /**
     * @When /^I call the "([^"]*)" api method to delete all repos$/
     * @param $host
     *
     * @throws Exception
     */
    public function iCallTheApiMethodToDeleteAllRepos($host)
    {
        if (is_array($this->availableRepos)) {
            foreach ($this->availableRepos as $repo) {
                if ($repo->name != self::MAIN_REPO) {
                    $response = $this->APIClass->callGitHubAPI($host . "/" . self::OWNER . "/$repo->name", "DELETE");
                    Asserts::assertRepoIsDeleted($response['Code']);
                }
            }
        } else {
            throw new \Exception('No available repos to delete');
        }
    }

    /**
     * @Then /^no repositories are available$/
     * @throws Exception
     */
    public function noRepositoriesAreAvailable()
    {
        Asserts::assertRemainingRepos(0, $this->availableRepos);
    }

    /**
     * @Given /^"([^"]*)" repository is present$/
     * @param $repoName
     *
     * @throws Exception
     */
    public function repositoryIsPresent($repoName)
    {
        Asserts::assertRepoIsPresent($repoName, $this->availableRepos);
    }

    /**
     * @Then /^the response code is correct for a failed delete$/
     * @throws Exception
     */
    public function theResponseCodeIsCorrectForAFailedDelete()
    {
        Asserts::assertRepoDeleteThatFails($this->responseCode);
    }


}