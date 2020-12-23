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
class send_cmcreatednotification extends \core\task\adhoc_task
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

        $msg = [
            'subject' => $course->shortname . ': New Module Added',
            'smallmessage' => '"' . $cm->name . '" was recently created in ' . $course->shortname,
            'fullmessage' => '"' . $cm->name . '" was recently created in ' . $course->shortname,
            'fullmessagehtml' => '<p>\'' . $cm->name . '\'was recently created created in ' . $course->shortname,
            'contexturl' => (string) $cm->url,
            'contexturlname' => $cm->name
        ];

        foreach ($students as $user) {
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
            $message->fullmessageformat = FORMAT_HTML;
            $message->fullmessagehtml = $msg['fullmessagehtml'];
            $message->smallmessage = $msg['smallmessage'];
            $message->contexturl = $msg['contexturl'];
            $message->contexturlname = $msg['contexturlname'];
            message_send($message);
        }
    }

}
