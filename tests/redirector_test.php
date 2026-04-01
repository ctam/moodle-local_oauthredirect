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

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/local/oauthredirect/classes/redirector.php');

/**
 * PHPUnit tests for the redirector helper.
 *
 * @covers \local_oauthredirect\redirector
 * @package    local_oauthredirect
 * @copyright  2026 OpenAI
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class redirector_test extends \advanced_testcase {

    /**
     * Test that a zero issuer id throws an exception.
     *
     * @return void
     */
    public function test_build_login_url_throws_on_zero_issuer() {
        $this->expectException('moodle_exception');

        redirector::build_login_url(0, false, null);
    }

    /**
     * Test that the basic URL includes id and wantsurl.
     *
     * @return void
     */
    public function test_build_login_url_basic_params() {
        global $DB;

        $this->resetAfterTest();

        $now = time();

        $record = new \stdClass();
        $record->name = 'test-issuer';
        $record->clientid = 'fake';
        $record->timecreated = $now;
        $record->timemodified = $now;

        $id = $DB->insert_record('oauth2_issuer', $record);

        $url = redirector::build_login_url($id, false, '/path');
        $params = $url->params();

        $this->assertArrayHasKey('id', $params);
        $this->assertEquals($id, (int) $params['id']);
        $this->assertArrayHasKey('wantsurl', $params);
        $this->assertEquals('/path', $params['wantsurl']);
        $this->assertArrayNotHasKey('sesskey', $params);
    }

    /**
     * Test that sesskey is included when requested.
     *
     * @return void
     */
    public function test_build_login_url_includes_sesskey_when_requested() {
        global $DB;

        $this->resetAfterTest();

        $now = time();

        $record = new \stdClass();
        $record->name = 'test-issuer-2';
        $record->clientid = 'fake2';
        $record->timecreated = $now;
        $record->timemodified = $now;

        $id = $DB->insert_record('oauth2_issuer', $record);

        $this->setUser($this->getDataGenerator()->create_user());

        $url = redirector::build_login_url($id, true, '/hm');
        $params = $url->params();

        $this->assertArrayHasKey('sesskey', $params);
        $this->assertNotEmpty($params['sesskey']);
    }
}