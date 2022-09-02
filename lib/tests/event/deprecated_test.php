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

namespace core\event;

/**
 * Tests for deprecated events.
 *
 * @package    core
 * @category   test
 * @copyright  2013 onwards Ankit Agarwal
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class deprecated_test extends \advanced_testcase {

    /**
     * Test event properties and methods.
     */
    public function test_deprecated_course_module_instances_list_viewed_events() {
        global $CFG;

        // Suppress debugging messages for a moment.
        $olddebug = $CFG->debug;
        $CFG->debug = 0;

        // Make sure the abstract class course_module_instances_list_viewed is deprecated.
        require_once(__DIR__ . '/../fixtures/event_mod_badfixtures.php');
        $this->assertTrue(\mod_unittests\event\course_module_instances_list_viewed::is_deprecated());

        $CFG->debug = $olddebug;
    }
}
