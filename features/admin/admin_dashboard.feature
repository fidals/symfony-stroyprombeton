Feature: Admin Dashboard

  Background:
    Given I am on "/admin"
    Then I fill in "username" with "admin"
    Then I fill in "password" with "spb2013"
    Then I press "_submit"

  Scenario: HTTP 200 OK
    Then the response status code should be 200

  Scenario: I should see dashboard
    Then I should see "Другое"
    And I should see "Каталог"
    And I should see "Табличный редактор продуктов"

  Scenario: I should see sidebar
    Then I should see an "#js-tree" element
    And I should see "Категории" in the "ul.sidebar-links" element

  Scenario Outline: Sidebar links should work
    When I follow "<entity>"
    Then the response status code should be 200
    Examples:
      | entity    |
      | Изделия   |
      | Категории |
      | Объекты   |

  Scenario Outline: Dashboard links should work
    When I click the element "a.<link>"
    Then the response status code should be 200
    Examples:
      | link           |
      | list-territory |
      | new-territory  |
      | new-product    |
      | list-product   |
      | tablegear-link |

