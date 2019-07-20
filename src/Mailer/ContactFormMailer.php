<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

/**
 * ContactForm mailer.
 */
class ContactFormMailer extends Mailer
{
    /**
     * Mailer's name.
     *
     * @var string
     */
    public static $name = 'ContactForm';

    public function submission(array $data)
    {
        $title = 'My Bookmarks';

        // Fluent interface: A fluent interface allows you to chain method calls
        $this->viewBuilder()
            ->setLayout('contact_form')
            ->setTemplate('default');

        $this
            ->setFrom('henry@chung.com', 'Bookmarks')
            ->setTo($data['email'], $data['name'])
            ->setTransport('default')
            ->setSubject('Test Chơi Thôi')
            ->set(compact('data', 'title'))
            ->setEmailFormat('both'); /* text/html/both */
    }
}
