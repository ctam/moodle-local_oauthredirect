<?php
// public endpoint: /local/oauthredirect/redirect.php
require_once(__DIR__ . '/../config.php');

defined('MOODLE_INTERNAL') || die();

$context = context_system::instance();
require_capability('local/oauthredirect:manage', $context);

// Read admin-configured defaults.
$defaultissuer = (int)get_config('local_oauthredirect', 'issuerid');
$include_sesskey = (bool)get_config('local_oauthredirect', 'include_sesskey');
$include_wantsurl = (bool)get_config('local_oauthredirect', 'include_wantsurl');

// Allow query overrides for convenience (optional).
$issuerid = optional_param('issuerid', $defaultissuer, PARAM_INT);
$forcesess = optional_param('sesskey', null, PARAM_TEXT); // if provided, will be used literally.
$wantsurl = optional_param('wantsurl', '', PARAM_LOCALURL);
$omit_sess_override = optional_param('omit_sesskey', 0, PARAM_INT);

// Determine whether to include sesskey: admin setting toggles, but explicit ?omit_sesskey=1 can drop it.
// If a literal 'sesskey' param is provided, use it (dangerous but useful for testing).
$use_sesskey = $include_sesskey && !$omit_sess_override;
$provided_sess = $forcesess !== null ? $forcesess : null;

// Build moodle_url via helper.
require_once(__DIR__ . '/classes/redirector.php');

try {
    if ($provided_sess !== null) {
        // temporarily set a flag to include provided sesskey by building URL manually
        $params = ['id' => $issuerid];
        if ($include_wantsurl && $wantsurl) {
            $params['wantsurl'] = $wantsurl;
        } else if ($include_wantsurl) {
            $params['wantsurl'] = '/';
        }
        if ($provided_sess !== '') {
            $params['sesskey'] = $provided_sess;
        }

        $target = new moodle_url('/auth/oauth2/login.php', $params);
    } else {
        $target = \local_oauthredirect\redirector::build_login_url($issuerid, $use_sesskey, $include_wantsurl ? $wantsurl : null);
    }
} catch (\moodle_exception $e) {
    // Show a friendly admin error page.
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('pluginname', 'local_oauthredirect'));
    echo $OUTPUT->notification($e->getMessage());
    echo $OUTPUT->footer();
    die();
}

// Finally redirect the browser.
redirect($target);
