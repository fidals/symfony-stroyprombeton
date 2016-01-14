Feature: 404

  Scenario Outline: 404 for non-existent pages
    Given I am on "<url>"
    Then the response status code should be 404

    Examples:
      | url                                      |
      | /1212                                    |
      | /page                                    |
      | 0e1a655621297dea072b099faae4d82c139a3a1d |

  Scenario Outline: 404 for non-existent Products
    Given I am on "/gbi/products/<productId>"
    Then the response status code should be 404

    Examples:
      | productId                                |
      | 122221234                                |
      | 666                                      |
      | 0e1a655621297dea072b099faae4d82c139a3a1d |
      | bugaga                                   |

  Scenario Outline: 404 for non-existent Categories
    Given I am on "/gbi/products/<categoryId>"
    Then the response status code should be 404

    Examples:
      | categoryId                               |
      | 2182212                                  |
      | 666                                      |
      | 0e1a655621297dea072b099faae4d82c139a3a1d |
      | bugaga                                   |