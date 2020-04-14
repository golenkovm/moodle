@tool @tool_task
Feature: See running scheduled tasks
  In order to configure scheduled tasks
  As an admin
  I need to be see if tasks are running

  Background:
    Given I log in as "admin"

  Scenario: If no tasks are running, I should not see that message
    When I navigate to "Server > Tasks > Tasks running now" in site administration
    Then I should see "No tasks are running now"

  @javascript
  Scenario: If tasks are running, I should see a message informing me about that
    When I pretend that the following tasks are running:
      | type      | classname                            | seconds | process           |
      | scheduled | \core\task\automated_backup_task     | 5       | c69335460f7f:1914 |
      | adhoc     | \core\task\asynchronous_backup_task  | 17      | c69335460f7f:1915 |
      | adhoc     | \core\task\asynchronous_restore_task | 121     | c69335460f7f:1916 |
    And I navigate to "Server > Tasks > Scheduled tasks" in site administration
    Then I should see "Tasks running now"
    And I should see "Last updated"

    # Check task details.
    And I should see "Scheduled" in the "Automated backups" "table_row"
    And I should see "Ad-hoc" in the "core\task\asynchronous_backup_task" "table_row"
    And I should see "Ad-hoc" in the "core\task\asynchronous_restore_task" "table_row"

    # Check times (either seconds or the other format for longer ones).
    And I should see "secs" in the "Automated backups" "table_row"
    And I should see "secs" in the "core\task\asynchronous_backup_task" "table_row"
    And I should see "0d 0h 2m" in the "core\task\asynchronous_restore_task" "table_row"

    # Check process info.
    And I should see "c69335460f7f:1914" in the "Automated backups" "table_row"
    And I should see "c69335460f7f:1915" in the "core\task\asynchronous_backup_task" "table_row"
    And I should see "c69335460f7f:1916" in the "core\task\asynchronous_restore_task" "table_row"

    # Check the AJAX refresh after finishing 2 tasks.
    And I pretend that the following tasks are running:
      | type      | classname                            | seconds | process |
      | scheduled | \core\task\automated_backup_task     |         | process |
      | adhoc     | \core\task\asynchronous_restore_task |         | process |
    And I press "Refresh"
    And I should not see "Automated backups" in the ".tool_task_running" "css_element"
    And I should not see "core\task\asynchronous_restore_task" in the ".tool_task_running" "css_element"
    And I should see "core\task\asynchronous_backup_task" in the ".tool_task_running" "css_element"
