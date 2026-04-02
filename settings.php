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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Admin settings for local_oauthredirect.
 *
 * @package    local_oauthredirect
 * @author     Carson Tam
 * @copyright  2026 The Regents of the University of California
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_oauthredirect', get_string('pluginname', 'local_oauthredirect'));

    global $DB;
    $settingoptions = [];

    try {
        $settingoptions = (array) $DB->get_records_menu('oauth2_issuer', null, 'id ASC', 'id, name');
    } catch (Exception $exception) {
        $settingoptions = [];
    }

    $settingoptions = [0 => get_string('none')] + $settingoptions;

    $settings->add(new admin_setting_configselect(
        'local_oauthredirect/issuerid',
        get_string('issuerid', 'local_oauthredirect'),
        get_string('issuerid_desc', 'local_oauthredirect'),
        0,
        $settingoptions
    ));

    $settings->add(new admin_setting_configcheckbox(
        'local_oauthredirect/include_sesskey',
        get_string('include_sesskey', 'local_oauthredirect'),
        get_string('include_sesskey_desc', 'local_oauthredirect'),
        1
    ));

    $settings->add(new admin_setting_configcheckbox(
        'local_oauthredirect/include_wantsurl',
        get_string('include_wantsurl', 'local_oauthredirect'),
        get_string('include_wantsurl_desc', 'local_oauthredirect'),
        1
    ));

    $ADMIN->add('localplugins', $settings);
}
