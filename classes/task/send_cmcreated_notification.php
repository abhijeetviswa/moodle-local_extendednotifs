<?php

namespace local_extendednotifs\task;

use moodle\moodle_exception;

include_once($CFG->dirroot . '/local/extendednotifs/lib_extendednotifs.php');

/**
 * Sends notifications for new course modules
 * @package    local_extendednotifs
 * @copyright  2020 Abhijeet Viswa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class send_cmcreated_notification extends \core\task\adhoc_task
{
    public function execute()
    {
        global $USER;

        $data = $this->get_custom_data();
        $courseid = $data->courseid;
        $cmid = $data->cmid;

        $students = extendednotifs_get_students_in_course($courseid);
        if (empty($students)) {
            return; // No one to notify
        }

        try {
            list($course, $cm) = get_course_and_cm_from_cmid($cmid);
        } catch (moodle_exception $e) {
            return; // module or course doesn't exist, don't bother continuing
        }

       $subject = $course->shortname . ': New Module Added';
       $smallmessage = '"' . $cm->name . '" was recently created in ' . $course->shortname;
       $fullmessage = '"' . $cm->name . '" was recently created in ' . $course->shortname;
       $fullmessagehtml = '<p>\'' . $cm->name . '\'was recently created created in ' . $course->shortname;
       $contexturl = (string) $cm->url;
       $contexturlname = $cm->name;

        $message = new \core\message\message();
        $message->component = 'local_extendednotifs';
        $message->name = 'cmcreated_notification';
        $message->courseid = $course->id;
        $message->modulename = $cm->name;
        $message->userfrom = $USER;
        $message->subject = $subject;
        $message->smallmessage = $smallmessage;;
        $message->fullmessage = $fullmessage;
        $message->fullmessageformat = FORMAT_HTML;
        $message->fullmessagehtml = $fullmessagehtml;
        $message->smallmessage = $smallmessage;
        $message->contexturl = $contexturl;
        $message->contexturlname = $contexturlname;

        foreach ($students as $user) {
            $message->userto = $user;
            message_send($message);
        }
    }

}
