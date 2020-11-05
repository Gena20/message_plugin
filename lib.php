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

require_once($CFG->dirroot . '/local/message/classes/form/editmessageform.php');

defined('MOODLE_INTERNAL') || die();

function local_message_before_footer() {
    global $DB, $USER;

    $sql = 'SELECT lm.id, lm.messagetext, lm.messagetype 
            FROM {local_message} AS lm 
            LEFT OUTER JOIN {local_message_read} AS lmr ON lm.id = lmr.message_id
            WHERE lmr.user_id <> :user_id 
               OR lmr.user_id IS NULL';
    $params = ['user_id' => $USER->id];
    $messages = $DB->get_records_sql($sql, $params);

    foreach ($messages as $message)
    {
        // Display the messages
        \core\notification::add($message->messagetext, editmessageform::MSG_TYPE[$message->messagetype]);

        $readrecord = new stdClass();
        $readrecord->message_id = $message->id;
        $readrecord->user_id = $USER->id;
        $readrecord->time_read = time();

        $DB->insert_record('local_message_read', $readrecord);
    }
}