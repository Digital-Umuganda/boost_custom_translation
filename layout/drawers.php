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
 * A drawer based layout for the ting theme.
 *
 * @package   theme_ting
 * @copyright 2021 Bas Brands
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

use moodle_url;
// print_r(['dir' => new moodle_url('/filter/translations/setcontentlanguage.php')]);

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

user_preference_allow_ajax_update('drawer-open-index', PARAM_BOOL);
user_preference_allow_ajax_update('drawer-open-block', PARAM_BOOL);

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING')) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}
$courseindex = core_course_drawer();
if (!$courseindex) {
    $courseindexopen = false;
}

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';
if ($PAGE->has_secondary_navigation()) {
    $tablistnav = $PAGE->has_tablist_secondary_navigation();
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);
$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);

$selectedLanguages = get_config('filter_translations', 'languages');

if (strstr($selectedLanguages, ',')) {
    $selectedLanguagesArray = explode(',', $selectedLanguages);
} else {
    $selectedLanguagesArray = [$selectedLanguages];
}

$selectedLanguage = get_config('filter_translations', 'contentlanguage');
$systemLanguage = current_language();
$languageList = [];

foreach (get_string_manager()->get_list_of_languages() as $code => $name) {
    $array = ['name' => $code, 'lang' => $name];
    if ($code == $systemLanguage) {
        $array['active'] = true;
    }
    $languageList[] = $array;
}

$systemLanguage = ['name'=> $systemLanguage, 'lang' => get_string_manager()->get_list_of_languages()[$systemLanguage]];

foreach ($selectedLanguagesArray as $key => $selected) {
    $array = ['name' => $selected, 'lang' => get_string_manager()->get_list_of_languages()[$selected], 'active' => false];
    if ($selectedLanguage == $selected) {
        $array['active'] = true;
    }
    $selectedLanguagesArray[] =  $array;
}

// print_r($selectedLanguagesArray);

$contentLanguage = ['name' => $selectedLanguage, 'lang' => get_string_manager()->get_list_of_languages()[$selectedLanguage]];
$setLanguageUrl = new moodle_url('/filter/translations/setcontentlanguage.php');

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'contentlanguages' => $selectedLanguagesArray,
    'contentlanguage' => $contentLanguage,
    'setlanguageurl' => $setLanguageUrl,
    'systemlanguage' => $systemLanguage,
    'expandicon' => $OUTPUT->image_url('expandsolid', 'theme'),
    'languages' => $languageList,
    'languageicon' => $OUTPUT->image_url('languagesolid', 'theme'),
    'maximizeicon' => $OUTPUT->image_url('maximizesolid', 'theme'),
    'minimizeicon' => $OUTPUT->image_url('minimizesolid', 'theme'),
    'courseid' => optional_param('course', null, PARAM_INT)
];

echo $OUTPUT->render_from_template('theme_boost_custom_translation/drawers', $templatecontext);
