Feature: Get price

  Background:
    Given I am on "/order_price/"

  Scenario: Http Status 200
    Then the response status code should be 200

  Scenario: Empty form shouldn't be submitted
    When I press "get-price-list"
    Then I should not see "Спасибо"
    
  Scenario: Form validation works
    When I fill in "price_list_booking[phone]" with "Not a number"
    When I fill in "price_list_booking[email]" with "not@a email"
    When I press "get-price-list"
    Then I should not see "Спасибо"

  Scenario: Form submit
    When I fill in "price_list_booking[phone]" with "+2(222) 222-2222"
    When I fill in "price_list_booking[email]" with "test@test.test"
    When I fill in "price_list_booking[company]" with "Компания"
    When I fill in "price_list_booking[city]" with "Санкт-Петербург"
    When I fill in "price_list_booking[activity]" with "Подрядная строительная организация"
    When I press "get-price-list"
    Then I should see "Спасибо"
