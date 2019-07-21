<?php
namespace App\Test\TestCase\Model\Behavior;

use App\Model\Behavior\UsersFindBehavior;
use App\Model\Table\BookmarksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Behavior\UsersFindBehavior Test Case
 */
class UsersFindBehaviorTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Behavior\UsersFindBehavior
     */
    public $Bookmarks;

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
        unset($this->UsersFind);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    public function testFindForUser()
    {
        // Chuẩn bị cái để assert
        $count = $this->Bookmarks->find('forUser', ['user_id' => 1])->count();
        // Assert
        $this->assertEquals(1, $count);

        // Chuẩn bị cái để assert
        $count = $this->Bookmarks->find('forUser', ['user_id' => 0])->count();
        // Assert
        $this->assertEquals(0, $count);
    }
}
