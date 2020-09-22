@mod @mod_data
Feature: Users can see export options

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    And the following "activities" exist:
      | activity | name               | intro          | course | idnumber |
      | data     | Test database name | Database intro | C1     | data1    |
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I add a "Text input" field to "Test database name" database and I fill the form with:
      | Field name        | Test field name        |
      | Field description | Test field description |
    # To generate the default templates.
    And I follow "Templates"
    And I wait until the page is ready
    And I log out

  Scenario: Teachers can see export options
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I follow "Test database name"
    And I follow "Export"
    Then I should see "Date fields in human-readable format"
    And I should see "With this option enabled the export file could not be re-imported later"
