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

$PAGE->set_url(new moodle_url('/local/message/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Manage messages');

$messages = $DB->get_records('local_message');
$messages = array_values($messages);

for ($i = 0; $i < count($messages); $i++) {
    $messages[$i]->messagetype = editmessageform::MSG_TYPE[$messages[$i]->messagetype];
}

echo $OUTPUT->header();

$templatecontext = (object)[
    'manageheader' => get_string('manageheader', 'local_message'),
    'messages' => array_values($messages),
    'editurl' => new moodle_url('/local/message/edit.php'),
    'createmessage' => get_string('createmessage', 'local_message'),
];

echo $OUTPUT->render_from_template('local_message/manage', $templatecontext);

echo $OUTPUT->footer();

