Feature: Admin list

  Background:
    Given I am on "/admin"
    Then I fill in "username" with "admin"
    Then I fill in "password" with "spb2013"
    Then I press "_submit"

  Scenario Outline: HTTP 200 OK
    Then I click the element "a.<list>"
    Then the response status code should be 200
    Examples:
      | list            |
      | list-product    |
      | list-category   |
      | list-territory  |
      | list-object     |
      | list-staticpage |
      | list-post       |

  Scenario Outline: Valid list table and pagination
    Then I click the element "a.<list>"
    Then I should see "<table-cell>"
    And I should see an "ul.pagination" element
    Examples:
      | list            | table-cell     |
      | list-product    | Диаметр внутр. |
      | list-category   | Марка          |
      | list-territory  | Транслит       |
      | list-object     | Субъект РФ     |
      | list-staticpage | Алиас          |
      | list-post       | Дата           |