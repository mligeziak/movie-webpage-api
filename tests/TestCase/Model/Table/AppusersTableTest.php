<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AppusersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AppusersTable Test Case
 */
class AppusersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\AppusersTable
     */
    public $Appusers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.appusers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Appusers') ? [] : ['className' => 'App\Model\Table\AppusersTable'];
        $this->Appusers = TableRegistry::get('Appusers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Appusers);

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
