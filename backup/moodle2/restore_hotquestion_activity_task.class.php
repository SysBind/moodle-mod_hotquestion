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

/**
 * Define all the backup steps that will be used by the backup_hotquestion_activity_task.
 *
 * @package mod_hotquestion
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/hotquestion/backup/moodle2/restore_hotquestion_stepslib.php'); // Because it exists (must).

/**
 * Hotquestion restore task that provides all the settings and steps to perform one complete restore of the activity.
 *
 * @package mod_hotquestion
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class restore_hotquestion_activity_task extends restore_activity_task {

    /**
     * Define add) particular settings this activity can have.
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }

    /**
     * Define(add) particular steps this activity can have.
     */
    protected function define_my_steps() {
        // Choice only has one structure step.
        $this->add_step(new restore_hotquestion_activity_structure_step('hotquestion_structure', 'hotquestion.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder.
     */
    public static function define_decode_contents() {
        $contents = array();

        $contents[] = new restore_decode_content('hotquestion', array('intro'), 'hotquestion');

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder.
     */
    public static function define_decode_rules() {
        $rules = array();

        $rules[] = new restore_decode_rule('HOTQUESTIONVIEWBYID', '/mod/hotquestion/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('HOTQUESTIONINDEX', '/mod/hotquestion/index.php?id=$1', 'course');

        return $rules;

    }

    /**
     * Define the restore log rules that will be applied
     * by the restore_logs_processor when restoring
     * hotquestion logs. It must return one array
     * of restore_log_rule objects.
     */
    public static function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('hotquestion', 'view', 'view.php?id={course_module}', '{hotquestion}');
        $rules[] = new restore_log_rule('hotquestion', 'add question', 'view.php?id={course_module}', '{hotquestion}');
        $rules[] = new restore_log_rule('hotquestion', 'update vote', 'view.php?id={course_module}', '{hotquestion}');
        $rules[] = new restore_log_rule('hotquestion', 'add round', 'view.php?id={course_module}', '{hotquestion}');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects.
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance cmid = 0).
     */
    public static function define_restore_log_rules_for_course() {
        $rules = array();

        // Fix old wrong uses (missing extension).
        $rules[] = new restore_log_rule('hotquestion', 'view all', 'index?id={course}', null,
                                        null, null, 'index.php?id={course}');
        $rules[] = new restore_log_rule('hotquestion', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}
