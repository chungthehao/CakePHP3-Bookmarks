<?php
namespace App\Test\TestCase\Controller;

use App\Controller\BookmarksController;
use App\Model\Table\BookmarksTable;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;

/**
 * App\Controller\BookmarksController Test Case
 */
class BookmarksControllerTest extends TestCase
{
    use IntegrationTestTrait;

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
        'app.Tags',
        'app.BookmarksTags'
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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        // Chuẩn bị data
        $newBookmark = ['title' => 'foo', 'url' => 'http://foo.bar', 'user_id' => 1];

        // Test việc add bookmark bằng
        $this->enableCsrfToken(); // POST method mà
        $this->enableSecurityToken(); // POST method mà?!
        $this->post('/bookmarks/add', $newBookmark);

        // Check việc redirect ok ko?
        $this->assertRedirect('/bookmarks');

        // Check coi có record đc lưu chưa
        $count = $this->Bookmarks->find('all')->where($newBookmark)->count();
        $this->assertEquals(1, $count);
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
