Feature: Admin login feature

  Background:
    Given I am on "/admin"

  Scenario: U shall not pass!
    When I fill in "username" with "Not an admin"
    And I fill in "password" with "spb2013"
    And I press "_submit"
    Then I should not see "Админка"

  Scenario: Login works
    When I fill in "username" with "admin"
    And I fill in "password" with "spb2013"
    And I press "_submit"
    Then I should see "Админка"