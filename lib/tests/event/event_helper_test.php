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

use advanced_testcase;

/**
 * Event helper tests.
 *
 * @package    core
 * @category   test
 * @covers     \core\event\event_helper
 * @author     Mikhail Golenkov <mikhailgolenkov@catalyst-au.net>
 * @copyright  2022 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class event_helper_test extends advanced_testcase {

    /**
     * Test set up.
     *
     * This is executed before running any test in this file.
     */
    public function setUp(): void {
        $this->resetAfterTest();
    }

    /**
     * Test that get_all_events() does not output any debugging messages.
     * @covers ::get_all_events()
     */
    public function test_get_all_events_debugging_not_called() {
        event_helper::get_all_events();
        $this->assertDebuggingNotCalled();
    }

    /**
     * Test that get_all_events() returns an array with expected structure.
     * @covers ::get_all_events()
     */
    public function test_get_all_events_returns_expected_structure() {
        $events = event_helper::get_all_events();
        $this->assertIsArray($events);

        // Pick an event and assert its structure.
        $this->assertArrayHasKey('\mod_assign\event\all_submissions_downloaded', $events);
        $event = [
            'eventname' => get_string('eventallsubmissionsdownloaded', 'mod_assign'),
            'component' => 'mod_assign',
            'target' => 'all_submissions',
            'action' => 'downloaded',
            'crud' => 'r',
            'edulevel' => 1,
            'objecttable' => 'assign',
            'eventclass' => '\mod_assign\event\all_submissions_downloaded',
            'legacyevent' => null,
            'since' => '2.6',
            'componentname' => get_string('pluginname', 'mod_assign', null, true),
        ];
        $this->assertEquals($event, $events['\mod_assign\event\all_submissions_downloaded']);
    }

    /**
     * Test that get_all_events() omits exceptional and abstract classes.
     * @covers ::get_all_events()
     */
    public function test_get_all_events_omits_exceptional_and_abstract_classes() {
        $events = event_helper::get_all_events();

        // Check exceptional classes that will cause problems if displayed.
        $this->assertArrayNotHasKey('\core\event\unknown_logged', $events);
        $this->assertArrayNotHasKey('\logstore_legacy\event\legacy_logged', $events);

        // Check abstract classes.
        $this->assertArrayNotHasKey('\core\event\base', $events);
        $this->assertArrayNotHasKey('\mod_assign\event\base', $events);
    }

    /**
     * Test that event_exists() returns expected value.
     * @covers ::event_exists()
     */
    public function test_event_exists() {
        $this->assertTrue(event_helper::event_exists('\mod_assign\event\submission_created'));
        $this->assertTrue(event_helper::event_exists('mod_assign\event\submission_created'));
        $this->assertTrue(event_helper::event_exists('\core\event\base'));
        $this->assertTrue(event_helper::event_exists('core\event\base'));
        $this->assertFalse(event_helper::event_exists('core\event\fake'));
    }
}
