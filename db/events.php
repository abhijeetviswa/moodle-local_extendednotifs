<?php
/**
 * @package    local_extendednotifs
 * @copyright  2020 Abhijeet Viswa
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$observers = array(
    // Handle attempt submitted event, as a way to send confirmation messages asynchronously.
    array(
        'eventname'   => 'core\event\course_module_created',
        'callback'    => 'local_extendednotifs_observer::course_module_created',
    ),
);
