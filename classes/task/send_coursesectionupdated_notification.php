<?php


namespace local_extendednotifs\task;

use moodle\moodle_exception;

include_once($CFG->dirroot . '/local/extendednotifs/lib_extendednotifs.php');

/**
 * Sends notifications for new course sections
 * @package    local_extendednotifs
 * @copyright  2020 Abhijeet Viswa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class send_coursesectionupdated_notification extends \core\task\adhoc_task
{
    public function execute()
    {
        global $USER;

        $data = $this->get_custom_data();
        $courseid = $data->courseid;
        $sectionnum = $data->sectionnum;

        $students = extendednotifs_get_students_in_course($courseid);
        if (empty($students)) {
            return; // no one to notify
        }

        try {
           $course = get_course($courseid);
           $modinfo = get_fast_modinfo($courseid);
           $section = $modinfo->get_section_info($sectionnum, MUST_EXIST);
        } catch (moodle_exception $e) {
            return;
        }

        $section_name = get_section_name($course, $section);
        $url_with_section = course_get_url($course, $section);

        $subject = $course->shortname . ': Section Updated';
        $smallmessage = 'The section "' . $section_name . '" was recently updated in ' . $course->shortname;
        $fullmessage = 'The section "' . $section_name . '" was recently updated in ' . $course->shortname;
        $fullmessagehtml = '<p>"' . $section_name . '" was recently created updated in ' . $course->shortname;
        $contexturl = (string) $url_with_section;
        $contexturlname = $course->shortname;

        $message = new \core\message\message();
        $message->component = 'local_extendednotifs';
        $message->name = 'coursesectionupdated_notification';
        $message->courseid = $course->id;
        $message->userfrom = $USER;
        $message->subject = $subject;
        $message->smallmessage = $smallmessage;
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