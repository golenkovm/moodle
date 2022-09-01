<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace core\event;

use core_component;
use ReflectionClass;
use logstore_legacy\event\legacy_logged;

/**
 * Event helper class.
 *
 * @package    core
 * @author     Mikhail Golenkov <mikhailgolenkov@catalyst-au.net>
 * @copyright  2022 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class event_helper {

    /**
     * Disables debugging and returns its current level.
     *
     * @return array An array of current values for debug, debugdisplay and debugdeveloper.
     */
    private static function disable_debugging(): array {
        global $CFG;

        $debuglevel = $CFG->debug;
        $debugdisplay = $CFG->debugdisplay;
        $debugdeveloper = $CFG->debugdeveloper;
        $CFG->debug = 0;
        $CFG->debugdisplay = false;
        $CFG->debugdeveloper = false;

        return [$debuglevel, $debugdisplay, $debugdeveloper];
    }

    /**
     * Restores debugging.
     *
     * @param array $currentdebug An array of values for debug, debugdisplay and debugdeveloper.
     */
    private static function restore_debugging(array $currentdebug): void {
        global $CFG;

        $CFG->debug = $currentdebug[0];
        $CFG->debugdisplay = $currentdebug[1];
        $CFG->debugdeveloper = $currentdebug[2];
    }

    /**
     * Returns the list of core and plugin events.
     * This excludes unknown_logged, legacy_logged and abstract classes.
     *
     * @return array
     */
    public static function get_all_events(): array {

        // Turn off debugging as deprecated events will fire warnings.
        $currentdebug = self::disable_debugging();

        // List of exceptional events that will cause problems if displayed.
        $eventsignore = [
            unknown_logged::class,
            legacy_logged::class,
        ];

        $eventlist = [];
        $events = core_component::get_component_classes_in_namespace(null, 'event');

        foreach (array_keys($events) as $event) {
            // We need to filter all classes that extend event base, or the base class itself.
            if (is_a($event, \core\event\base::class, true) && !in_array($event, $eventsignore)) {

                $reflectionclass = new ReflectionClass($event);
                if (!$reflectionclass->isAbstract()) {
                    $eventdetails = $event::get_static_info();
                    $eventclass = $eventdetails['eventname'];
                    $eventdetails['eventclass'] = $eventclass;
                    $eventdetails['eventname'] = $event::get_name_with_info();
                    $eventdetails['legacyevent'] = $event::get_legacy_eventname();

                    $eventdocbloc = $reflectionclass->getDocComment();
                    $sincepattern = "/since\s*Moodle\s([0-9]+.[0-9]+)/i";
                    preg_match($sincepattern, $eventdocbloc, $result);
                    if (isset($result[1])) {
                        $eventdetails['since'] = $result[1];
                    } else {
                        $eventdetails['since'] = null;
                    }

                    if ($eventdetails['component'] != 'core') {
                        $eventdetails['componentname'] = get_string('pluginname', $eventdetails['component'], null, true);
                    } else {
                        $eventdetails['componentname'] = get_string('core', 'moodle', null, true);
                    }

                    $eventlist[$eventclass] = $eventdetails;
                }
            }
        }

        // Restore debugging.
        self::restore_debugging($currentdebug);

        return $eventlist;
    }

    /**
     * Checks if an event class exists.
     *
     * @param string $eventclass Event class to be checked.
     * @return bool
     */
    public static function event_exists(string $eventclass): bool {
        // Turn off debugging as deprecated events will fire warnings.
        $currentdebug = self::disable_debugging();

        $eventclass = trim($eventclass, '\\');
        $classparts = explode('\\', $eventclass);
        $events = core_component::get_component_classes_in_namespace($classparts[0], 'event');

        // Restore debugging.
        self::restore_debugging($currentdebug);

        return in_array($eventclass, array_keys($events));
    }
}
