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
 * Language strings for local_oauthredirect.
 *
 * @package    local_oauthredirect
 * @author     Carson Tam
 * @copyright  2026 The Regents of the University of California
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['include_sesskey'] = 'Include sesskey';
$string['include_sesskey_desc'] = 'If enabled, the current session sesskey will be appended to the OAuth redirect URL.';
$string['include_wantsurl'] = 'Include wantsurl';
$string['include_wantsurl_desc'] = 'If enabled, wantsurl will be added to the redirect from ?wantsurl= or a default value.';
$string['invalidissuer'] = 'OAuth issuer with id {$a} not found.';
$string['issuerid'] = 'Default OAuth issuer';
$string['issuerid_desc'] = 'Select the OAuth issuer to which the redirect should point by default.';
$string['missingissuer'] = 'Missing OAuth issuer id.';
$string['none'] = 'None';
$string['pluginname'] = 'OAuth Redirect';
