<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * This is a one-line short description of the file.
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    local_notification
 * @copyright  2019 Douglas Metcalfe
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function local_notification_before_footer()
{

    global $DB, $USER;

    $sql = "SELECT mes.id, mes.notificationtext, mes.notificationtype 
    FROM {local_notification} mes 
    left outer join {local_notification_read} mesread ON mes.id = mesread.notificationid
    WHERE mesread.userid <> :userid
    OR mesread.userid IS NULL";

    $params = [
        'userid' => $USER->id,
    ];

    $notifications = $DB->get_records_sql($sql, $params);

    foreach ($notifications as $notification) {
        $type = \core\output\notification::NOTIFY_INFO;
        if ($notification->notificationtype === '0') {
            $type = \core\output\notification::NOTIFY_WARNING;
        } else if ($notification->notificationtype === '1') {
            $type = \core\output\notification::NOTIFY_SUCCESS;
        } else if ($notification->notificationtype === '2') {
            $type = \core\output\notification::NOTIFY_ERROR;
        }
        \core\notification::add($notification->notificationtext, $type);

        $readrecord = new stdClass();
        $readrecord->notificationid = $notification->id;
        $readrecord->userid = $USER->id;
        $readrecord->timeread = time();
        $DB->insert_record('local_notification_read', $readrecord);
    }
}
