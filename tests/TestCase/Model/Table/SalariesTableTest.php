<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SalariesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SalariesTable Test Case
 */
class SalariesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SalariesTable
     */
    public $Salaries;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.salaries',
        'app.employees',
        'app.shops'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Salaries') ? [] : ['className' => 'App\Model\Table\SalariesTable'];
        $this->Salaries = TableRegistry::get('Salaries', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Salaries);

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
