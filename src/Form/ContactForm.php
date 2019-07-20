<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Mailer\Email;
use Cake\Validation\Validator;

/**
 * Contact Form.
 */
class ContactForm extends Form
{
    /**
     * Builds the schema for the modelless form
     *
     * @param \Cake\Form\Schema $schema From schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        $schema->addFields([
            'name' => ['type' => 'string'],
            'email' => ['type' => 'string'],
        ]);

        return $schema;
    }

    /**
     * Form validation builder
     *
     * @param \Cake\Validation\Validator $validator to use against the form
     * @return \Cake\Validation\Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('name')
            ->notEmptyString('name')

            ->requirePresence('email')
            ->add('email', 'emailHopLe', ['rule' => 'email'])
            ->notEmptyString('email');

        return $validator;
    }

    /**
     * Defines what to execute once the From is being processed
     *
     * @param array $data Form data.
     * @return bool
     */
    protected function _execute(array $data)
    {
        $email = new Email();

        // Fluent interface: A fluent interface allows you to chain method calls
        $email
            ->setFrom('henry@chung.com', 'Bookmarks')
            ->setTo($data['email'], $data['name'])
            ->setTemplate('default')
            ->setTransport('default')
            ->setViewVars(compact('data'))
            ->send();

        return true;
    }
}
