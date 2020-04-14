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
 * This file contains the unit tests for the task manager.
 *
 * @package   core
 * @copyright 2019 Brendan Heywood <brendan@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This file contains the unit tests for the task manager.
 *
 * @copyright 2019 Brendan Heywood <brendan@catalyst-au.net>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_task_manager_testcase extends advanced_testcase {

    public function test_ensure_adhoc_task_qos_provider() {
        return [
            [
                [],
                [],
            ],
            // A queue with a lopside initial load that needs to be staggered.
            [
                [
                    (object)['id' => 1, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 2, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 3, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 4, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 5, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 6, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 7, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 8, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 9, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 10, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 11, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 12, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 13, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 14, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 15, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                ],
                [
                    (object)['id' => 1, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 7, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 2, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 8, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 3, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 9, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 4, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 10, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 5, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 6, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 11, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 12, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 13, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 14, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 15, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                ],
            ],
            // The same lopsided queue but now the first item is gone.
            [
                [
                    (object)['id' => 2, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 3, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 4, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 5, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 6, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 7, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 8, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 9, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                ],
                [
                    (object)['id' => 7, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 2, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 8, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 3, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 9, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 4, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 5, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 6, 'classname' => '\core\task\asynchronous_backup_task'],
                ],
            ],
            // The same lopsided queue but now the first two items is gone.
            [
                [
                    (object)['id' => 3, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 4, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 5, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 6, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 7, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 8, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 9, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                ],
                [
                    (object)['id' => 3, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 7, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 4, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 8, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 5, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 9, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 6, 'classname' => '\core\task\asynchronous_backup_task'],
                ],
            ],
            // The same lopsided queue but now the first three items are gone.
            [
                [
                    (object)['id' => 4, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 5, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 6, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 7, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 8, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 9, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                ],
                [
                    (object)['id' => 7, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 4, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 8, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 5, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 9, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 6, 'classname' => '\core\task\asynchronous_backup_task'],
                ],
            ],
            [
                [
                    (object)['id' => 5, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 6, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 7, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 8, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                    (object)['id' => 9, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                ],
                [
                    (object)['id' => 5, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 7, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],

                    (object)['id' => 6, 'classname' => '\core\task\asynchronous_backup_task'],
                    (object)['id' => 8, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],

                    (object)['id' => 9, 'classname' => '\tool_dataprivacy\task\process_data_request_task'],
                ],
            ],
        ];
    }

    /**
     * Reduces a list of tasks into a simpler string
     *
     * @param array $input array of tasks
     * @return string list of task ids
     */
    function flatten($tasks) {
        $list = '';
        foreach ($tasks as $id => $task) {
            $list .= ' ' . $task->id;
        }
        return $list;
    }

    /**
     * Test that the Quality of Service reordering works.
     *
     * @dataProvider test_ensure_adhoc_task_qos_provider
     *
     * @param array $input array of tasks
     * @param array $expected array of reordered tasks
     * @return void
     */
    public function test_ensure_adhoc_task_qos(array $input, array $expected) {
        $this->resetAfterTest();
        $result = \core\task\manager::ensure_adhoc_task_qos($input);


        $result = $this->flatten($result);
        $expected = $this->flatten($expected);

        $this->assertEquals($expected, $result);
    }

    /**
     * Provider for test_should_clear_caches_after_change.
     *
     * @return array
     */
    public function test_should_clear_caches_after_change_provider() {
        return [
            ['', false],
            ['non_cron_related_setting', false],
            ['cron_enabled', true],
            ['task_scheduled_concurrency_limit', true],
            ['task_scheduled_max_runtime', true],
            ['task_adhoc_concurrency_limit', true],
            ['task_adhoc_max_runtime', true],
        ];
    }

    /**
     * Test should_clear_caches_after_change() static method.
     *
     * @dataProvider test_should_clear_caches_after_change_provider
     * @param string $settingname Name of setting that was changed.
     * @param boolean $expected Expected result.
     * @return void
     */
    public function test_should_clear_caches_after_change($settingname, $expected) {
        $result = \core\task\manager::should_clear_caches_after_change($settingname);
        $this->assertEquals($expected, $result);
    }
}

