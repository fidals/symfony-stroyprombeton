Feature: Filters on list pages

  Background:
    Given I am on "/admin"
    Then I fill in "username" with "admin"
    Then I fill in "password" with "spb2013"
    Then I press "_submit"

  Scenario Outline: Filter by name works
    Given I am on "<list>"
    When I fill in "name" with "<name>"
    And I click the element "button.btn-primary"
    Then I should not see "<another_name>" in the "table.table" element
    When I fill in "name" with "<another_name>"
    And I click the element "button.btn-primary"
    Then I should not see "<name>" in the "table.table" element
    When I click the element "a.btn-danger"
    Then I should be on "<list>"
    Examples:
      | list                   | name         | another_name |
      | /admin/category/list   | марши        | урны         |
      | /admin/product/list    | надолб       | урны         |
      | /admin/territory/list  | петербург    | челябинск    |
      | /admin/object/list     | лэп          | переход      |
      | /admin/post/list       | конструкции  | новогодних   |
      | /admin/staticpage/list | производство | компании     |

  Scenario Outline: Filter by id works
    Given I am on "<list>"
    When I fill in "id" with "<id>"
    And I click the element "button.btn-primary"
    Then I should not see "<another_id>" in the "table.table" element
    When I fill in "id" with "<another_id>"
    And I click the element "button.btn-primary"
    Then I should not see "<id>" in the "table.table" element
    When I click the element "a.btn-danger"
    Then I should be on "<list>"
    Examples:
      | list                   | id    | another_id |
      | /admin/category/list   | 15    | 20         |
      | /admin/product/list    | 643   | 15081      |
      | /admin/territory/list  | 12488 | 12555      |
      | /admin/object/list     | 12439 | 12449      |
      | /admin/post/list       | 12537 | 13951      |
      | /admin/staticpage/list | 624   | 7          |

  Scenario Outline: Filter by parent works
    Given I am on "<list>"
    When I fill in "<parent_field>" with "<parent_id>"
    And I click the element "button.btn-primary"
    Then I should not see "<another_parent_name>" in the "table.table" element
    When I fill in "<parent_field>" with "<another_parent_id>"
    And I click the element "button.btn-primary"
    Then I should not see "<parent_name>" in the "table.table" element
    When I click the element "a.btn-danger"
    Then I should be on "<list>"
    Examples:
      | list                 | parent_field | parent_id | parent_name                     | another_parent_id | another_parent_name         |
      | /admin/category/list | parent       | 462       | Cтойки для опор контактной сети | 435               | Балконные плиты             |
      | /admin/product/list  | category     | 244       | Блоки открылков                 | 347               | Звенья труб. Серия 3.501-59 |
      | /admin/object/list   | territory    | 12515     | Ивановская область              | 14738             | Курская область             |

  Scenario Outline: Filter by isActive works
    Given I am on "<list>"
    When I uncheck "isActive"
    And I click the element "button.btn-primary"
    Then I should not see "Да" in the "table.table" element
    When I click the element "button.btn-primary"
    Then I should not see "Нет" in the "table.table" element
    When I click the element "a.btn-danger"
    Then I should be on "<list>"
    Examples:
      | list                   |
      | /admin/category/list   |
      | /admin/product/list    |
      | /admin/territory/list  |
      | /admin/object/list     |