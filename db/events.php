<?php
/**
 * @package    local_extendednotifs
 * @copyright  2020 Abhijeet Viswa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname'   => 'core\event\course_module_created',
        'callback'    => 'local_extendednotifs_observer::course_module_created',
    ),
    array(
        'eventname'   => 'core\event\course_section_updated',
        'callback'    => 'local_extendednotifs_observer::course_section_updated',
    ),
);
