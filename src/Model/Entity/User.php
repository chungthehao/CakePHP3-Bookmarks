<?php
namespace App\Model\Entity;

use Cake\Log\LogTrait;
use Psr\Log\LogLevel;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $firstname
 * @property string $lastname
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Bookmark[] $bookmarks
 */
class User extends Entity
{
    # Trait: Cung cấp (các) method cho mình dùng.
    # Import the trait & use it in our class
    use LogTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'email' => true,
        'password' => true,
        'firstname' => true,
        'lastname' => true,
        'created' => true,
        'modified' => true,
        'bookmarks' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'password'
    ];

    # Getter có thể là xào nấu trên real field cùng tên hoặc virtual field (no value being passed ở params)
    protected function _getName()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    # Setter: Muốn set thì phải tồn tại giá trị này thiệt -> CHẮC CHẮN có param pass vô.
    protected function _setPassword($password)
    {
        $this->log('User changing password', LogLevel::DEBUG);
        return $password; // Bắt buộc có nha! (Vì ý nghĩa của setter là sẽ set cái gì ở đây trả về cho field đó.)
    }
}
