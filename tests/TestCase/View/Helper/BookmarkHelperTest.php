<?php
namespace App\Test\TestCase\View\Helper;

use App\View\Helper\BookmarkHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\BookmarkHelper Test Case
 */
class BookmarkHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\View\Helper\BookmarkHelper
     */
    public $Bookmark;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->Bookmark = new BookmarkHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Bookmark);

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

    public function testUrl()
    {
        // Chuẩn bị 1 instance của Bookmark entity để pass vô url để test
        $data = [
            'title' => 'TITLE',
            'url' => 'http://test.com'
        ];
        $bookmark = new \App\Model\Entity\Bookmark($data);

        // $this->Bookmark: Instance of BookmarkHelper
        $output = $this->Bookmark->url($bookmark);
        $expected = '<a href="http://test.com" target="_blank" title="TITLE">http://test.com</a>';

        // Assert: output đúng cái expected
        $this->assertEquals($expected, $output);
        # Chạy: vendor/bin/phpunit tests/TestCase/View/Helper/BookmarkHelperTest.php để test
    }
}
