<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BookingsHasServicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BookingsHasServicesTable Test Case
 */
class BookingsHasServicesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BookingsHasServicesTable
     */
    public $BookingsHasServices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.bookings_has_services',
        'app.bookings',
        'app.customers',
        'app.shops',
        'app.billings',
        'app.billings_has_services',
        'app.services',
        'app.categories',
        'app.employees'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('BookingsHasServices') ? [] : ['className' => 'App\Model\Table\BookingsHasServicesTable'];
        $this->BookingsHasServices = TableRegistry::get('BookingsHasServices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BookingsHasServices);

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
