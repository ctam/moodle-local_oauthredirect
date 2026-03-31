<?php
defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/local/oauthredirect/classes/redirector.php');

class local_oauthredirect_redirector_testcase extends advanced_testcase {

    public function test_build_login_url_throws_on_empty_issuer() {
        $this->expectException('moodle_exception');
        \local_oauthredirect\redirector::build_login_url(0, false, null);
    }

    public function test_build_login_url_basic_params() {
        global $DB, $CFG;
        $this->resetAfterTest();

        // Insert a fake issuer to the DB so the helper finds it.
        $record = new stdClass();
        $record->name = 'test-issuer';
        $record->clientid = 'fake';
        $record->authorizationendpoint = '';
        $record->tokenendpoint = '';
        $record->userinfoendpoint = '';
        $record->jwksuri = '';
        $record->issuerurl = 'https://example';
        $id = $DB->insert_record('oauth2_issuer', $record);

        // Build URL without sesskey (we cannot assert the real sesskey here)
        $url = \local_oauthredirect\redirector::build_login_url($id, false, '/mytarget');
        $this->assertInstanceOf('moodle_url', $url);
        $qs = $url->get_query();
        $this->assertArrayHasKey('id', $qs);
        $this->assertEquals($id, (int)$qs['id']);
        $this->assertArrayHasKey('wantsurl', $qs);
        $this->assertEquals('/mytarget', $qs['wantsurl']);
        $this->assertArrayNotHasKey('sesskey', $qs);
    }

    public function test_build_login_url_includes_sesskey_when_requested() {
        global $DB;
        $this->resetAfterTest();

        $record = new stdClass();
        $record->name = 'test-issuer-2';
        $record->clientid = 'fake2';
        $record->authorizationendpoint = '';
        $record->tokenendpoint = '';
        $record->userinfoendpoint = '';
        $record->jwksuri = '';
        $record->issuerurl = 'https://example2';
        $id = $DB->insert_record('oauth2_issuer', $record);

        // Create a session for the test so sesskey() returns a string.
        $this->setUser($this->getDataGenerator()->create_user());
        $this->get_session(); // ensure session started

        $url = \local_oauthredirect\redirector::build_login_url($id, true, '/hm');
        $qs = $url->get_query();
        $this->assertArrayHasKey('sesskey', $qs);
        $this->assertNotEmpty($qs['sesskey']);
    }
}
