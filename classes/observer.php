<?php
/**
 * @package    local_extendednotifs
 * @copyright  2020 Abhijeet Viswa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_extendednotifs\task;

defined('MOODLE_INTERNAL') || die();

class local_extendednotifs_observer {
    public static function course_module_created(\core\event\course_module_created $event) {
        global $USER;

        $task = new task\send_new_cm_notifications();
        $task->set_custom_data([
            'courseid' => $event->courseid,
            'cmid' => $event->objectid,
        ]);
        $task->set_userid($USER->id);
        \core\task\manager::queue_adhoc_task($task);
    }

    public static function course_section_updated(\core\event\course_section_updated $event) {
        global $USER;

        $task = new task\send_coursesectionupdated_notification();
        $task->set_custom_data([
            'courseid' => $event->courseid,
            'sectionnum' => $event->other['sectionnum'],
        ]);
        $task->set_userid($USER->id);
        \core\task\manager::queue_adhoc_task($task);
    }
}
