# Extended Notifications Plugin for Moodle
By default, Moodle does not send notifications for certain events like creating new course modules,
updating course sections etc. This plugin intends to extend the cases when notifications are sent
out to user.

Currently, notifications are sent for the following:

* When new modules are created.
* When existing course sections are modified.

The plugin uses Moodle Event API to be listen to these events and uses the Tasks API to send
notifications to users.

## Installation
This plugin can be installed by cloning this repo into the `<moodle-src-root>\local`. Ensure that
the containing folder is named `extendednotifs` and not `moodle_local-extendednotifs`.
