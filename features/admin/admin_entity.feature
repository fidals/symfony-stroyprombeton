Feature: Admin Entity Feature

  Background:
    Given I am on "/admin"
    Then I fill in "username" with "admin"
    Then I fill in "password" with "spb2013"
    Then I press "_submit"

  Scenario Outline: I can see basic blocks and valid header in every EntityPage
    Given I am on "<entity>"
    Then the response status code should be 200
    And I should see "<name>" in the "h1" element
    And I should see "Основные характеристики" in the "div.entity-wrapper" element
    And I should see "Дополнительные характеристики" in the "div.chars-wrapper" element
    And I should see "SEO" in the "div.seo-wrapper" element
    Examples:
      | entity                 | name               |
      | /admin/category/edit   | Категория          |
      | /admin/product/edit    | Изделие            |
      | /admin/territory/edit  | Территория         |
      | /admin/object/edit     | Объект             |
      | /admin/post/edit       | Новость            |
      | /admin/staticpage/edit | Статичная страница |

  Scenario Outline: I can edit existing Entity via EntityPage
    Given I am on "<entity>"
    Then the response status code should be 200
    When I fill in "<name>[name]" with "Отредактированная сущность"
    And I press "<name>[save]"
    Then I should be on "<list>"
    And I should see "Отредактированная сущность"
    Examples:
      | entity                      | name        | list                   |
      | /admin/category/edit/11     | category    | /admin/category/list   |
      | /admin/product/edit/641     | product     | /admin/product/list    |
      | /admin/territory/edit/12488 | territory   | /admin/territory/list  |
      | /admin/object/edit/12445    | object      | /admin/object/list     |
      | /admin/post/edit/12531      | post        | /admin/post/list       |
      | /admin/staticpage/edit/7    | static_page | /admin/staticpage/list |
