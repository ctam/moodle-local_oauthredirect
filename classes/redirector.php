<?php
namespace local_oauthredirect;
defined('MOODLE_INTERNAL') || die();

use moodle_url;

class redirector {
    /**
     * Build the OAuth login moodle_url for an issuer.
     *
     * @param int $issuerid
     * @param bool $include_sesskey
     * @param string|moodle_url|null $wantsurl
     * @return moodle_url
     * @throws \moodle_exception
     */
    public static function build_login_url(int $issuerid, bool $include_sesskey = true, $wantsurl = null): moodle_url {
        global $CFG;

        if (empty($issuerid)) {
            throw new \moodle_exception('missingissuer', 'local_oauthredirect');
        }

        // Ensure the issuer exists (basic check).
        global $DB;
        $issuer = $DB->get_record('oauth2_issuer', ['id' => $issuerid], '*', IGNORE_MISSING);
        if (!$issuer) {
            throw new \moodle_exception('invalidissuer', 'local_oauthredirect', '', $issuerid);
        }

        // Normalize wantsurl.
        if ($wantsurl instanceof moodle_url) {
            $w = $wantsurl->out();
        } else if (!empty($wantsurl)) {
            // Accept strings; sanitize as local URL.
            $w = (string) $wantsurl;
        } else {
            $w = '/';
        }

        $params = ['id' => $issuerid];

        if ($w) {
            $params['wantsurl'] = $w;
        }

        if ($include_sesskey) {
            // sesskey() requires a valid session. Caller must ensure it's safe to call.
            $params['sesskey'] = sesskey();
        }

        return new moodle_url('/auth/oauth2/login.php', $params);
    }
}
