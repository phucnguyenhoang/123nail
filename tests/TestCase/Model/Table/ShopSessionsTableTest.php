<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShopSessionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShopSessionsTable Test Case
 */
class ShopSessionsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ShopSessionsTable
     */
    public $ShopSessions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.shop_sessions'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('ShopSessions') ? [] : ['className' => 'App\Model\Table\ShopSessionsTable'];
        $this->ShopSessions = TableRegistry::get('ShopSessions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShopSessions);

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
     * Test createSession method
     *
     * @return void
     */
    public function testCreateSession()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
