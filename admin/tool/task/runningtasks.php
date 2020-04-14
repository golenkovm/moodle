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
 * Running task admin page.
 *
 * @package    tool_task
 * @copyright  2020 Catalyst IT
 * @author     Mikhail Golenkov <mikhailgolenkov@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->libdir.'/tablelib.php');

admin_externalpage_setup('runningtasks');

$task = null;
$mform = null;

$renderer = $PAGE->get_renderer('tool_task');

echo $OUTPUT->header();
$PAGE->requires->js_call_amd('tool_task/runningtasks', 'init');
$PAGE->requires->strings_for_js(['ok', 'error'], 'moodle');
$PAGE->requires->strings_for_js(['errorloading'], 'tool_task');

$running = core\task\manager::get_running_tasks();
echo $renderer->running_tasks_table($running);

echo $OUTPUT->footer();

