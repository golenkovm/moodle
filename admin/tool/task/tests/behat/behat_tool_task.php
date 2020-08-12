<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Behat step definitions for scheduled task administration.
 *
 * @package tool_task
 * @copyright 2017 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

use \Behat\Gherkin\Node\TableNode;

require_once(__DIR__ . '/../../../../../lib/behat/behat_base.php');

/**
 * Behat step definitions for scheduled task administration.
 *
 * @package tool_task
 * @copyright 2017 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_tool_task extends behat_base {

    /**
     * Set a fake fail delay for a scheduled task.
     *
     * @Given /^the scheduled task "(?P<task_name>[^"]+)" has a fail delay of "(?P<seconds_number>\d+)" seconds$/
     * @param string $task Task classname
     * @param int $seconds Fail delay time in seconds
     */
    public function scheduled_task_has_fail_delay_seconds($task, $seconds) {
        global $DB;
        $id = $DB->get_field('task_scheduled', 'id', ['classname' => $task], IGNORE_MISSING);
        if (!$id) {
            throw new Exception('Unknown scheduled task: ' . $task);
        }
        $DB->set_field('task_scheduled', 'faildelay', $seconds, ['id' => $id]);
    }

    /**
     * Set up some tasks to be 'running' (not really).
     *
     * @param TableNode $data List of tasks
     * @Given /^I pretend that the following tasks are running:$/
     * @throws Exception
     */
    public function i_pretend_that_the_following_tasks_are_running(TableNode $data) {
        global $DB;

        foreach ($data->getHash() as $rowdata) {
            // Check and get the data from the user-entered row.
            $fields = array_flip(['type', 'classname', 'seconds', 'hostname', 'pid']);
            foreach ($rowdata as $key => $value) {
                if (!array_key_exists($key, $fields)) {
                    throw new Exception('Unexpected field "' . $key);
                }
            }
            // You can set the seconds, hostname and pid fields blank to 'stop' task running,
            // but classname and type fields must be set.
            if (empty($rowdata['classname']) || empty($rowdata['type'])) {
                throw new Exception('Classname and type fields must be set');
            }

            // Check type task.
            if ($rowdata['type'] !== 'adhoc' && $rowdata['type'] !== 'scheduled') {
                throw new Exception('Type must be adhoc or scheduled');
            }

            if ($rowdata['type'] === 'scheduled') {
                // For scheduled tasks, find the matching row and set it.
                $record = $DB->get_record('task_scheduled', ['classname' => $rowdata['classname']]);
                if (empty($rowdata['seconds'])) {
                    $record->timestarted = null;
                    $record->hostname = null;
                    $record->pid = null;
                    $DB->update_record('task_scheduled', $record);
                } else {
                    $record->timestarted = time() - $rowdata['seconds'];
                    $record->hostname = $rowdata['hostname'];
                    $record->pid = $rowdata['pid'];
                    $DB->update_record('task_scheduled', $record);
                }
            } else {
                // For ad-hoc tasks, add or delete a row.
                if (!empty($rowdata['seconds'])) {
                    $faketask = (object)[
                        'classname' => $rowdata['classname'],
                        'nextruntime' => 0,
                        'timestarted' => time() - $rowdata['seconds'],
                        'hostname' => $rowdata['hostname'],
                        'pid' => $rowdata['pid'],
                    ];
                    $DB->insert_record('task_adhoc', $faketask);
                } else {
                    $DB->delete_records('task_adhoc', ['classname' => $rowdata['classname']]);
                }
            }
        }
    }
}
