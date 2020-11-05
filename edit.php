<?php
// This file is part of Moodle Course Rollover Plugin
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
 * @package     local_message
 * @author      Kristian
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/message/classes/form/editmessageform.php');

global $DB;

$PAGE->set_url(new moodle_url('/local/message/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Edit');


$mform = new editmessageform();



if ($mform->is_cancelled()) {
    // Go back to manage page
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('messageformcancelled', 'local_message'));
} else if ($fromform = $mform->get_data()) {
    // Insert data into put database table
    $record = new stdClass();
    $record->messagetext = $fromform->messagetext;
    $record->messagetype = $fromform->messagetype;

    $DB->insert_record('local_message', $record);

    // Go back to manage page
    redirect($CFG->wwwroot . '/local/message/manage.php',
            get_string('messageformsubmited', 'local_message') . ": $record->messagetext");

}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();