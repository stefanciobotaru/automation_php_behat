Feature: GitHub add, delete repositories

  @add
  Scenario Outline: Successfully create 3 distinct repositories and assert that the response code is correct
    Given a valid data structure to add a new repository with <name>
    When I call the "https://api.github.com/user/repos" api method to add a repo
    Then a new repository is created

    Examples:
      | name  |
      | Repo1 |
      | Repo2 |
      | Repo3 |

  @delete_one
  Scenario: Delete successfully one of them and assert that the response code is correct
    Given I call the "https://api.github.com/user/repos" to get all repos
    Then the response code is correct for list repos call
    When save one to be deleted
    And I call the "https://api.github.com/repos" api method to delete the saved repo
    Then the selected repo is successfully deleted

  @delete_dummy
  Scenario: Try to delete again one of the repositories and assert that the response code is correct for a failed delete
    Given a dummy repo to delete is saved
    When I call the "https://api.github.com/repos" api method to delete the saved repo
    Then the response code is correct for a failed delete

  @remaining
  Scenario: List the remaining repositories and assert the response code is correct, assert that the 2
  remaining repositories are still present and also assert that the deleted repository is
  present(this assert should fail)
    Given I call the "https://api.github.com/user/repos" to get all repos
    Then the response code is correct for list repos call
    And "2" remaining repositories are still present
    And "Repo1" repository is present

  @delete_all
  Scenario: Delete all repositories
    Given I call the "https://api.github.com/user/repos" to get all repos
    Then the response code is correct for list repos call
    When I call the "https://api.github.com/repos" api method to delete all repos
    And I call the "https://api.github.com/user/repos" to get all repos
    Then no repositories are available