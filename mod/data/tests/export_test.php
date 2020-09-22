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
 * Unit tests for export.php.
 *
 * @package    mod_data
 * @category   test
 * @copyright  2020 Mikhail Golenkov <golenkovm@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Unit tests for export.php.
 *
 * @package    mod_data
 * @copyright  2020 Mikhail Golenkov <golenkovm@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_data_export_test extends advanced_testcase {

    /**
     * Set up function.
     */
    protected function setUp() {
        parent::setUp();
        $this->resetAfterTest(true);
    }

    /**
     * Test export_text_value() method exports data in both human-readable and timestamp format.
     */
    public function test_export_text_value_date() {
        $course = $this->getDataGenerator()->create_course();
        $data = $this->getDataGenerator()->create_module('data', array('course' => $course->id));
        $field = data_get_field((object)['type' => 'date', 'dataid' => $data->id], 0);
        $content = (object)['content' => '1600654289'];
        $actual1 = $field->export_text_value($content, ['humanreadabledate' => true]);
        $actual2 = $field->export_text_value($content, ['humanreadabledate' => false]);
        $actual3 = $field->export_text_value($content, []);
        $this->assertEquals('Monday, 21 September 2020, 10:11 AM', $actual1);
        $this->assertEquals('1600654289', $actual2);
        $this->assertEquals('1600654289', $actual3);
    }
}
