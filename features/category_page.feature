Feature: Section page

  Background:
    Given I am on "/gbi/categories/218/"

  Scenario: Http Status 200
    Then the response status code should be 200

  Scenario: Actual H1
    Then I should see "Металлические конструкции непроходных кабельных эстакад. Серия 3.016.2-12, выпуск 2" in the "h1" element

  @javascript
  Scenario Outline: Order button works
    When I fill the element "#shk-count-<productId>" with "<count>"
    When I click the element "shk-submit-<productId>" with JS
    And I hover over the element "div#cartInner"
    Then I should see "<product>" in the "#dropdown-basket" element
    And I should see "<count>" in the "#dropdown-basket" element

    Examples:
      | product                      | count | productId |
      | Фундамент железобетонный Фм1 | 10    | 6901      |
      | Фундамент железобетонный Фм9 | 666   | 6914      |
      | Фундамент железобетонный Фм6 | 10101 | 6910      |