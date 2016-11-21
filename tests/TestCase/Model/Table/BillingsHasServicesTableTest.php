<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BillingsHasServicesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BillingsHasServicesTable Test Case
 */
class BillingsHasServicesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\BillingsHasServicesTable
     */
    public $BillingsHasServices;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.billings_has_services',
        'app.billings',
        'app.customers',
        'app.shops',
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
        $config = TableRegistry::exists('BillingsHasServices') ? [] : ['className' => 'App\Model\Table\BillingsHasServicesTable'];
        $this->BillingsHasServices = TableRegistry::get('BillingsHasServices', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BillingsHasServices);

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
