<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ApiUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ApiUsersTable Test Case
 */
class ApiUsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ApiUsersTable
     */
    public $ApiUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.api_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ApiUsers') ? [] : ['className' => 'App\Model\Table\ApiUsersTable'];
        $this->ApiUsers = TableRegistry::get('ApiUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ApiUsers);

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
