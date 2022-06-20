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
 * CLI script to clean up bad question data.
 *
 * @package    core
 * @subpackage cli
 * @author     Mikhail Golenkov <mikhailgolenkov@catalyst-au.net>
 * @copyright  2022 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('CLI_SCRIPT', true);
require(__DIR__.'/../../config.php');
require_once($CFG->libdir . '/questionlib.php');

mtrace('Searching for questions that have parent field set to an id that does not exist.').
$sql = 'SELECT q1.id, q1.parent
          FROM {question} q1
     LEFT JOIN {question} q2
            ON q1.parent = q2.id
         WHERE q2.id IS NULL
           AND q1.parent <> ?';
$records = $DB->get_recordset_sql($sql, [0]);
$count = 0;
foreach ($records as $record) {
    $DB->set_field('question', 'parent', 0, ['id' => $record->id]);
    $count++;
}
mtrace('  Updated ' . $count . ' questions.');
$records->close();

mtrace('Searching for questions that have parent field set to an id that belongs to a different question category.');
$sql = 'SELECT q1.id, q2.category AS parentcategory
          FROM {question} q1,
               {question} q2
         WHERE q1.parent = q2.id
           AND q1.category <> q2.category';
$records = $DB->get_recordset_sql($sql);
$count = 0;
foreach ($records as $record) {
    $DB->set_field('question', 'category', $record->parentcategory, ['id' => $record->id]);
    $count++;
}
$records->close();
mtrace('  Updated ' . $count . ' questions.');

mtrace('Searching for questions that are linked to a category that does not exist.');
$sql = 'SELECT q.*
          FROM {question} q
     LEFT JOIN {question_categories} qc
            ON q.category = qc.id
         WHERE qc.id IS NULL';
$records = $DB->get_recordset_sql($sql);
$countmoved = 0;
$countdeleted = 0;
$topcategory = question_make_default_categories([\context_system::instance()]);
foreach ($records as $record) {
    if (questions_in_use([$record->id])) {
        question_move_questions_to_category([$record->id], $topcategory->id);
        $countmoved++;
    } else {
        question_delete_question($record->id);
        $countdeleted++;
    }
}
$records->close();
mtrace('  Moved ' . $countmoved . ' and deleted ' . $countdeleted . ' questions.');
