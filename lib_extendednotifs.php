<?php
/**
 * @package    local_extendednotifs
 * @copyright  2020 Abhijeet Viswa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function extendednotifs_get_students_in_course($courseid): array {
    global $DB;

    $context = context_course::instance($courseid);
    $role = $DB->get_record('role', array('shortname'=> 'student'));
    $users = get_users_from_role_on_context($role, $context);

    if (empty($users)) {
        return [];
    }

    $ids = [];
    foreach ($users as $user) {
        $ids[] = $user->userid;
    }

    $sql = "SELECT * FROM {user} where id in (" . join(",", $ids) . ")";
    $students = $DB->get_records_sql($sql);

    return $students;
}
