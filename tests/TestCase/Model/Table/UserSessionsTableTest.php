<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserSessionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserSessionsTable Test Case
 */
class UserSessionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UserSessionsTable
     */
    public $UserSessions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.user_sessions',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('UserSessions') ? [] : ['className' => 'App\Model\Table\UserSessionsTable'];
        $this->UserSessions = TableRegistry::get('UserSessions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserSessions);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
