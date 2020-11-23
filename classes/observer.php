<?php
/**
 * @package    local_extendednotifs
 * @copyright  2020 Abhijeet Viswa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

include_once($CFG->dirroot . '/local/extendednotifs/lib_extendednotifs.php');

class local_extendednotifs_observer {
    public static function course_module_created(\core\event\course_module_created $event) {
        global $USER;

        /*
            $event->objectid => Module id
            $event->courseid => Obviously the course id
        */
        $users = extendednotifs_get_students_in_course($event->courseid);
        list($course, $cm) = get_course_and_cm_from_cmid($event->objectid);

        $msg = [
            'subject' => 'New Module: ' . $course->shortname,
            'smallmessage' => 'New Module: ' . $course->shortname,
            'fullmessage' => "'" . $cm->name . '\' was recently created in ' . $course->shortname,
            'fullmessagehtml' => '<p>\'' . $cm->name . '\'was recently created created in ' . $course->shortname,
            'contexturl' => $cm->url,
            'contexturlname' => $cm->name
        ];

        foreach ($users as $user) {
            $message = new \core\message\message();
            $message->component = 'local_extendednotifs';
            $message->name = 'cmcreated_notification';
            $message->courseid = $course->id;
            $message->modulename = $cm->name;
            $message->userfrom = $USER;
            $message->userto = $user;
            $message->subject = $msg['subject'];
            $message->smallmessage = $msg['smallmessage'];
            $message->fullmessage = $msg['fullmessage'];
            $message->fullmessageformat = FORMAT_MARKDOWN;
            $message->fullmessagehtml = $msg['fullmessagehtml'];
            $message->smallmessage = $msg['smallmessage'];
            $message->contexturl = $msg['contexturl'];
            $message->contexturlname = $msg['contexturlname'];
            message_send($message);
        }
    }
}
