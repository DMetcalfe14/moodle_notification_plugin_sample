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

require_once(__DIR__ . '/../../config.php');

global $DB;

$PAGE->set_url(new moodle_url('/local/notification/delete.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Delete notifications');

$context = context_system::instance();

if (has_capability('local/notification:delete', $context)) {
  $DB->delete_records('local_notification', array('id' => htmlspecialchars($_GET['id'])));
  redirect($CFG->wwwroot . '/local/notification/manage.php', 'Notification Deleted');
} else {
  redirect($CFG->wwwroot . '/', 'You do not have permission to access this resource');
}
