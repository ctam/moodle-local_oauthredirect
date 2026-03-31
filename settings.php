<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Only managers/admins should be able to set this.
    $settings = new admin_settingpage('local_oauthredirect_settings', get_string('pluginsettings', 'local_oauthredirect'));

    // Build issuer options from DB.
    global $DB;
    $options = array();
    try {
        $records = $DB->get_records_menu('oauth2_issuer', null, 'id ASC', 'id, name');
        if ($records) {
            $options = $records;
        }
    } catch (Exception $e) {
        // If table not present or any error, leave $options empty.
        $options = array();
    }

    $options = array(0 => get_string('none')) + $options;

    $settings->add(new admin_setting_configselect(
        'local_oauthredirect/issuerid',
        get_string('issuerid', 'local_oauthredirect'),
        get_string('issuerid_desc', 'local_oauthredirect'),
        0,
        $options
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
