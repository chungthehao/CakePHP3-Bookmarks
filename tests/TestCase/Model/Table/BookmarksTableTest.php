<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BookmarksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BookmarksTable Test Case
 */
class BookmarksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BookmarksTable
     */
    public $Bookmarks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Bookmarks',
        'app.Users',
        'app.Tags'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Bookmarks') ? [] : ['className' => BookmarksTable::class];
        $this->Bookmarks = TableRegistry::getTableLocator()->get('Bookmarks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Bookmarks);

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

    public function testNotUrl()
    {
        $realUrl = 'http://google.com';
        $context = [];
        $this->assertFalse($this->Bookmarks->notUrl($realUrl, $context));

        $normalString = 'Hu len chay';
        $context = [];
        $this->assertTrue($this->Bookmarks->notUrl($normalString, $context));
    }
}
