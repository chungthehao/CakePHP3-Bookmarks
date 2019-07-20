<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Form\ContactForm;

/**
 * Contacts Controller
 *
 */
class ContactsController extends AppController
{
    public function index()
    {
        $form = new ContactForm();

        if ($this->request->is('post')) {
            // Thực hiện việc validation dùng ContactForm
            if ($form->execute($this->request->getData())) {
                $this->Flash->success('We have received your contact request!');
            } else {
                $this->Flash->error('Check the form!');
            }
        }

        $this->set(compact('form'));
    }
}
