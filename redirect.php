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
 * Redirect endpoint for OAuth login.
 *
 * @package    local_oauthredirect
 * @author     Carson Tam
 * @copyright  2026 The Regents of the University of California
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// phpcs:disable moodle.Files.RequireLogin.Missing
require_once(__DIR__ . '/../../config.php');
// phpcs:enable moodle.Files.RequireLogin.Missing

defined('MOODLE_INTERNAL') || die();

$defaultissuerid = (int) get_config('local_oauthredirect', 'issuerid');
$includesesskey = (bool) get_config('local_oauthredirect', 'include_sesskey');
$includewantsurl = (bool) get_config('local_oauthredirect', 'include_wantsurl');

$issuerid = optional_param('issuerid', $defaultissuerid, PARAM_INT);

$wantsurl = null;
if ($includewantsurl) {
    $wantsurl = optional_param('wantsurl', '/', PARAM_LOCALURL);
}

$omitsskey = optional_param('omit_sesskey', 0, PARAM_BOOL);
$usesesskey = $includesesskey && !$omitsskey;

try {
    $target = \local_oauthredirect\redirector::build_login_url($issuerid, $usesesskey, $wantsurl);
} catch (\moodle_exception $exception) {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('pluginname', 'local_oauthredirect'));
    echo $OUTPUT->notification($exception->getMessage(), 'notifyproblem');
    echo $OUTPUT->footer();
    exit;
}

redirect($target);
