Feature: Main page

  Background:
    Given I am on "/"

  Scenario: Http Status 200
    Then the response status code should be 200

  @javascript
  Scenario: Empty cart dropdown
    When I hover over the element "#cartInner"
    Then I should see "Нет выбранных позиций."

  @accordion
  @javascript
  Scenario Outline: Accordion works
    Then the element "#content-<root-category>" should not be visible
    When I click the element "cat-<root-category>" with JS
    Then the element "#content-<root-category>" should be visible
    And I should see "<children>" in the "#content-<root-category>" element

    Examples:
      | root-category | children                        |
      | 456           | Козырьки                        |
      | 457           | Подпорные стены                 |