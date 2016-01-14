Feature: Search page

  Background:
    Given I am on "/"

  @javascript
  Scenario Outline: Search for root categories should produce at least one link as a result
    When I fill in "search_condition" with "<query>"
    And I click the element "btn-search-action" with JS
    And I wait "1" seconds
    Then I should see "<query>" in the ".gbi-list" element
    Examples:
      | query                                        |
      | Дорожное строительство                       |
      | Строительство энергетических объектов        |
      | Общегражданское и промышленное строительство |
      | Строительство инженерных сетей               |
      | Благоустройство территорий                   |
      | Нефтегазовое строительство                   |

  @javascript
  Scenario Outline: Search for "труба" and "зп" should produce expected results
    When I fill in "search_condition" with "<query>"
    And I click the element "btn-search-action" with JS
    And I wait "1" seconds
    Then I should see "<result>" in the ".gbi-list" element
    Examples:
      | query | result                              |
      | труба | [ГОСТ 6482-88] Труба Т 100.50-3     |
      | зп    | [Шифр 2119РЧ] Звено входное ЗП 200В |

  @javascript
  Scenario: Autocomplete appears
    When I fill in "search_condition" with "зп"
    And I wait "2" seconds
    Then I should see an "ul.ui-autocomplete" element