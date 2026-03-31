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

require_once(__DIR__ . '/../../config.php');

/**
 * Public redirect endpoint for OAuth login.
 *
 * @package    local_oauthredirect
 * @copyright  2026 OpenAI
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This endpoint is intentionally public, so the login-check sniff is disabled.
// phpcs:disable moodle.Files.RequireLogin.Missing

defined('MOODLE_INTERNAL') || die();

$defaultIssuerid = (int) get_config('local_oauthredirect', 'issuerid');
$includeSesskey = (bool) get_config('local_oauthredirect', 'include_sesskey');
$includeWantsurl = (bool) get_config('local_oauthredirect', 'include_wantsurl');

$issuerid = optional_param('issuerid', $defaultIssuerid, PARAM_INT);
$wantsurl = null;
if ($includeWantsurl) {
    $wantsurl = optional_param('wantsurl', '/', PARAM_LOCALURL);
}
$omitSesskey = optional_param('omit_sesskey', 0, PARAM_BOOL);

$useSesskey = $includeSesskey && !$omitSesskey;

require_once(__DIR__ . '/classes/redirector.php');

try {
    $target = \local_oauthredirect\redirector::build_login_url($issuerid, $useSesskey, $wantsurl);
} catch (\moodle_exception $exception) {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('pluginname', 'local_oauthredirect'));
    echo $OUTPUT->notification($exception->getMessage(), 'notifyproblem');
    echo $OUTPUT->footer();
    exit;
}

redirect($target);

// phpcs:enable moodle.Files.RequireLogin.Missing