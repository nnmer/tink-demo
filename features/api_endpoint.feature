Feature: api endpoint test

  Scenario: execute get /api/accounts
    Given I call "/api/accounts"
    Then the response is JSON
    And the response status code should be 200

  Scenario: execute post /api/accounts/open
    Given that I send "{"name": "Account owner name","identityId": "PD34234dd"}"
    And I call "/api/accounts/open"
    Then the response is JSON
    And the response status code should be 200
    And the response has a "number" property
    And the response has a "owner" property

  Scenario: execute post /api/accounts/{number}/close
    Given that I send "{}"
    And I call "/api/accounts/400931510716515/close"
    Then the response is JSON
    And the response status code should be 200
    And the "number" property equals "400931510716515"
    And the "active" property equals "false"

  Scenario: execute post /api/accounts/{number}/balance
    Given I call "/api/accounts/400931510724562/balance"
    Then the response is JSON
    And the response status code should be 200
    And the "number" property equals "400931510724562"
    And the "balance" property equals "1300.00"

  Scenario: withdraw when execute post /api/accounts/{number}/withdraw/{amount}
    Given I call "/api/accounts/400931510694839/withdraw/10"
    Then the response is JSON
    And the response status code should be 200
    And the "number" property equals "400931510694839"
    And the "balance" property equals "58"

  Scenario: deposit when execute post /api/accounts/{number}/deposit/{amount}
    Given that I put "{}"
    And I call "/api/accounts/400931510694839/deposit/35"
    Then the response is JSON
    And the response status code should be 200
    And the "number" property equals "400931510694839"
    And the "balance" property equals "93"

  Scenario: transfer money, execute post /api/accounts/transfer
    Given that I send "{"fromAccount": "400931510724562","toAccount": "400931510694840","amount": 100}"
    And I call "/api/accounts/transfer"
    And the response status code should be 200
