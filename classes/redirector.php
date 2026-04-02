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

namespace local_oauthredirect;

/**
 * Builds OAuth redirect URLs.
 *
 * @package    local_oauthredirect
 * @copyright  2026 OpenAI
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class redirector {
    /**
     * Build the OAuth login URL.
     *
     * @param int $issuerid The OAuth issuer ID.
     * @param bool $includesesskey Whether to include sesskey in the URL.
     * @param string|null $wantsurl The wantsurl value, if any.
     * @return \moodle_url
     * @throws \moodle_exception If the issuer cannot be found.
     */
    public static function build_login_url(int $issuerid, bool $includesesskey = true, ?string $wantsurl = null): \moodle_url {
        global $DB;

        if ($issuerid < 1) {
            throw new \moodle_exception('missingissuer', 'local_oauthredirect');
        }

        $issuer = $DB->get_record('oauth2_issuer', ['id' => $issuerid], '*', IGNORE_MISSING);
        if (!$issuer) {
            throw new \moodle_exception('invalidissuer', 'local_oauthredirect', '', $issuerid);
        }

        $params = ['id' => $issuerid];

        if ($wantsurl !== null && $wantsurl !== '') {
            $params['wantsurl'] = $wantsurl;
        }

        if ($includesesskey) {
            $params['sesskey'] = sesskey();
        }

        return new \moodle_url('/auth/oauth2/login.php', $params);
    }
}
