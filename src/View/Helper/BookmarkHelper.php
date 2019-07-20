<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * Bookmark helper
 */
class BookmarkHelper extends Helper
{
    public $helpers = ['Html'];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    // This method to only accept entities of bookmarks
    public function url(\App\Model\Entity\Bookmark $bookmark)
    {
        $showName = $bookmark->url;
        $href = $bookmark->url;
        return $this->Html->link(
            $showName,
            $href,
            ['target' => '_blank', 'title' => $bookmark->title]
        );
    }
}
