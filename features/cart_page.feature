Feature: Cart Page

  @javascript
  Scenario: We are on order page
    Given I am on "/gbi/products/6204"
    Then I fill in "shk-count" with "10"
    Then I click the element "shk-submit-6204" with JS
    Then I click the element "div#cartInner"
    # У нас такая крутая верстка, в которой нельзя заполнить некоторые инпуты со станд. драйвером,
    # так что обходимся без Background, в котором невозможно использование Селениума
    And I should see "Оформление заказа на ЖБИ продукцию" in the "h1" element

  @javascript
  Scenario: Dropdown controls works
    Given I am on "/gbi/products/6204"
    Then I fill in "shk-count" with "10"
    Then I click the element "shk-submit-6204" with JS
    Then I click the element "div#cartInner"
    ##
    When I hover over the element "div#cartInner"
    Then I should see "Итого" in the "#dropdown-basket" element
    When I click the element "a.shk-del"
    And I hover over the element "div#cartInner"
    Then I should not see "Итого" in the "#dropdown-basket" element
    And I should see "Нет выбранных позиций." in the "#dropdown-basket" element
    And I should see "Нет выбранных позиций" in the "div#order-table" element

  @javascript
  Scenario Outline: Table controls works
    Given I am on "/gbi/products/6204"
    Then I fill in "shk-count" with "10"
    Then I click the element "shk-submit-6204" with JS
    Then I click the element "div#cartInner"
    And I wait "2" seconds
    ##
    When I fill the element "#count-6204" with "<count>"
    And I hover over the element "div#cartInner"
    Then I should see "<count>" in the "#dropdown-basket" element
    Examples:
      | count |
      | 100   |
      | 200   |
      | 20    |

  @javascript
  Scenario Outline: Form validation works
    Given I am on "/gbi/products/6204"
    Then I fill in "shk-count" with "10"
    Then I click the element "shk-submit-6204" with JS
    Then I click the element "div#cartInner"
    And I wait "2" seconds
    ##
    When I fill in "order[person]" with "<person>"
    When I fill in "order[phone]" with "<phone>"
    When I fill in "order[email]" with "<email>"
    When I fill in "order[company]" with "<company>"
    When I click the element "btn-order-send" with JS
    Then I should not see "Ваш заказ принят"
    Examples:
      | person      | phone       | email          | company      |
      |             | 22222222222 | test@test.test | Test company |
      | Some Person | 22222222    | test@test.test | Test company |
      | Some Person | 22222222222 | em.ail2112a    | Test company |
      | Some Person | 22222222222 | em.ail2112     |              |


  @javascript
  Scenario: We can send Order
    Given I am on "/gbi/products/6204"
    Then I fill in "shk-count" with "10"
    Then I click the element "shk-submit-6204" with JS
    Then I click the element "div#cartInner"
    And I wait "2" seconds
    ##
    When I fill in "order[person]" with "I'm just a test"
    When I fill in "order[phone]" with "22222222222"
    When I fill in "order[email]" with "test@test.test"
    When I fill in "order[company]" with "Test company"
    When I click the element "btn-order-send" with JS
    And I wait "2" seconds
    Then I should see "Ваш заказ принят"


