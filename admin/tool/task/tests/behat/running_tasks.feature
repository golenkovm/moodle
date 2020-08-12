@tool @tool_task
Feature: See running scheduled tasks
  In order to configure scheduled tasks
  As an admin
  I need to be see if tasks are running

  Background:
    Given I log in as "admin"

  Scenario: If no tasks are running, I should not see that message
    When I navigate to "Server > Tasks > Tasks running now" in site administration
    Then I should see "Nothing to display"

  @javascript
  Scenario: If tasks are running, I should see a message informing me about that
    When I pretend that the following tasks are running:
      | type      | classname                            | seconds | hostname     | pid  |
      | scheduled | \core\task\automated_backup_task     | 5       | c69335460f7f | 1914 |
      | adhoc     | \core\task\asynchronous_backup_task  | 121     | c69335460f7f | 1915 |
      | adhoc     | \core\task\asynchronous_restore_task | 3601    | c69335460f7f | 1916 |
    And I navigate to "Server > Tasks > Tasks running now" in site administration

    # Check task details.
    Then I should see "Scheduled" in the "\core\task\automated_backup_task" "table_row"
    And I should see "Ad-hoc" in the "\core\task\asynchronous_backup_task" "table_row"
    And I should see "Ad-hoc" in the "\core\task\asynchronous_restore_task" "table_row"

    # Check times.
    And I should see "secs" in the "Automated backups" "table_row"
    And I should see "2 mins" in the "core\task\asynchronous_backup_task" "table_row"
    And I should see "1 hour" in the "core\task\asynchronous_restore_task" "table_row"

    # Check hostname and pid details.
    And I should see "c69335460f7f" in the "Automated backups" "table_row"
    And I should see "1914" in the "Automated backups" "table_row"
    And I should see "c69335460f7f" in the "core\task\asynchronous_backup_task" "table_row"
    And I should see "1915" in the "core\task\asynchronous_backup_task" "table_row"
    And I should see "c69335460f7f" in the "core\task\asynchronous_restore_task" "table_row"
    And I should see "1916" in the "core\task\asynchronous_restore_task" "table_row"

    # Check the AJAX refresh after finishing 2 tasks.
    And I pretend that the following tasks are running:
      | type      | classname                            | seconds | hostname     | pid  |
      | scheduled | \core\task\automated_backup_task     |         |              |      |
      | adhoc     | \core\task\asynchronous_restore_task |         |              |      |
    And I navigate to "Server > Tasks > Tasks running now" in site administration
    And I should not see "Automated backups"
    And I should not see "core\task\asynchronous_restore_task"
    And I should see "core\task\asynchronous_backup_task"
