<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Bookmarks Controller
 *
 * @property \App\Model\Table\BookmarksTable $Bookmarks
 *
 * @method \App\Model\Entity\Bookmark[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BookmarksController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users']
        ];
        $bookmarks = $this->paginate($this->Bookmarks);

        $this->set(compact('bookmarks'));
    }

    public function export($limit = 100) // ~/bookmarks/export/2
    {
        $bookmarks = $this->Bookmarks->find('all')->limit($limit)->where([
            'user_id' => 1
        ])->contain([
            'Tags' => function ($q) {
                return $q->where([
                    'Tags.name LIKE' => '%t%'
                ]);
            }
        ]);

        $this->set('bookmarks', $bookmarks);
    }

    /**
     * View method
     *
     * @param string|null $id Bookmark id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $bookmark = $this->Bookmarks->get($id, [
            'contain' => ['Users', 'Tags']
        ]);

        $this->set('bookmark', $bookmark);
    }

    /**
     * Add method (Render form để add + Process khi submit form đó)
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        // - $this->Bookmarks: instance of App\Model\Table\BookmarksTable (Deal with saving & reading data from the database)
        $bookmark = $this->Bookmarks->newEntity(); // Creating a new entity (a new single row from the table of a bookmark)
        // - $bookmark: entity instance (App\Model\Entity\Bookmark)

        if ($this->request->is('post')) {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->getData()); // Đưa data từ form vô new instance đó
            if ($this->Bookmarks->save($bookmark)) {
                $this->Flash->success(__('The bookmark has been saved.'));

                return $this->redirect(['action' => 'index']); // Ko chỉ rõ controller, hiểu là controller hiện tại.
            }
            $this->Flash->error(__('The bookmark could not be saved. Please, try again.'));
        }

        // * Display or Redisplay (when save failed) the add form
        $users = $this->Bookmarks->Users->find('list', ['limit' => 200]); // Lấy qua quan hệ
        $tags = $this->Bookmarks->Tags->find('list', ['limit' => 200]); // Lấy qua quan hệ

        // Set some view variables (use in view layer)
        $this->set(compact('bookmark', 'users', 'tags'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Bookmark id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bookmark = $this->Bookmarks->get($id, [
            'contain' => ['Tags']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->getData());
            if ($this->Bookmarks->save($bookmark)) {
                $this->Flash->success(__('The bookmark has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bookmark could not be saved. Please, try again.'));
        }
        $users = $this->Bookmarks->Users->find('list', ['limit' => 200]);
        $tags = $this->Bookmarks->Tags->find('list', ['limit' => 200]);
        $this->set(compact('bookmark', 'users', 'tags'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Bookmark id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bookmark = $this->Bookmarks->get($id);
        if ($this->Bookmarks->delete($bookmark)) {
            $this->Flash->success(__('The bookmark has been deleted.'));
        } else {
            $this->Flash->error(__('The bookmark could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
