Feature: Product page

  Scenario Outline: Actual H1
    When I am on "/gbi/products/<productId>/"
    Then the response status code should be 200
    And I should see "<H1>" in the "h1" element
    And I should see "<H1>" in the "title" element

    Examples:
      | productId | H1                                 |
      | 6204      | Звено ЗКП 125.1.300                |
      | 1559      | Урна железобетонная МС-700         |
      | 10102     | Нижний блок шахты лифта ШЛГП63п-11 |


  @javascript
  Scenario Outline: Product order
    Given I am on "/gbi/products/6204"
    When I fill in "shk-count" with "<count>"
    When I click the element "shk-submit-6204" with JS
    And I hover over the element "div#cartInner"
    Then I should see "<count>" in the "#dropdown-basket" element

    Examples:
      | count |
      | 10    |
      | 15    |
      | 10101 |


