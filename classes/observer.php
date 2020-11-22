<?php
/**
 * @package    local_extendednotifs
 * @copyright  2020 Abhijeet Viswa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class local_extendednotifs_observer {
    public static function course_module_created(\core\event\course_module_created  $event) {
        global $USER;

        /*
            $event->objectid => Module id
            $event->courseid => Obviously the course id
        */
        $message = new \core\message\message();
        $message->component = 'local_extendednotifs';
        $message->name = 'cm_created_notification';
        $message->userfrom = $USER;
        $message->userto = 2;
        $message->subject = 'course_module_created';
        $message->fullmessage = 'course_module_created';
        $message->fullmessageformat = FORMAT_MARKDOWN;
        $message->fullmessagehtml = '<p>course_module_Created</p>';
        $message->smallmessage = 'course_module_created';
        $message->contexturl = 'http://GalaxyFarFarAway.com';
        $message->contexturlname = 'Context name';
        $message->replyto = "random@example.com";
        $message->courseid = 2;

        message_send($message);
    }
}
