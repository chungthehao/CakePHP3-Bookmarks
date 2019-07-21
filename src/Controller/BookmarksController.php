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
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Validate');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        // * Chỉ ra layout sẽ dùng
        // $this->viewBuilder()->setLayout('ajax');

        // * Flash component & Flash element
        // $this->Flash->default('Ahihi!');

        $this->paginate = [
            'contain' => ['Users', 'Tags']
        ];
        $bookmarks = $this->paginate($this->Bookmarks);

        $this->set(compact('bookmarks'));
    }

    public function export($limit = 100) // ~/bookmarks/export/2
    {
        // * Dùng custom component
        $limit = $this->Validate->validLimit($limit, 100);

        // * Dùng custom behavior 'UsersFindBehavior'
        // | Lúc sau: dùng custom plugin UsersFind (code ở đây ko đổi, đổi chỗ load behavior thôi)
        $bookmarks = $this->Bookmarks
            ->find('forUser', ['user_id' => 1])
            ->limit($limit);
            /*->contain([
                'Tags' => function ($q) {
                    return $q->where([
                        'Tags.name LIKE' => '%t%'
                    ]);
                }
            ]);*/

        /*$bookmarks = $this->Bookmarks->find('all')->limit($limit)->where([
            'user_id' => 1
        ])->contain([
            'Tags' => function ($q) {
                return $q->where([
                    'Tags.name LIKE' => '%t%'
                ]);
            }
        ]);*/

        // Chuẩn bị 3 thứ cho CsvView
        $this->set('_serialize', 'bookmarks');
        $this->set('_header', ['Title', 'URL']);
        $this->set('_extract', ['title', 'url']);
        // CsvView plugin uses a custom view class to engage its behavior (view classes: AppView class, AjaxView class)
        $this->viewBuilder()->setClassName('CsvView.Csv');
        // 'CsvView.Csv' CakePHP knows to search the plugin, named 'CsvView', for the 'Csv' view class
        $this->setResponse($this->getResponse()->withDownload('my-bookmarks.csv')); // custom tên của file
        $this->set('bookmarks', $bookmarks);
    }

    # To explore collections
    public function collectionTest()
    {
        // We're NOT going to attempt to render a view file with this method
        $this->autoRender = false;

        //--------------------------------------------------------------------

        $bookmarks = $this->Bookmarks->find('list');

        debug("Each");
        $bookmarks->each(function ($value, $key) {
            echo "Element $key: $value" . '<br />';
        });

        //--------------------------------------------------------------------
        echo '----------------------------------------------------------<br />';

        $bookmarks = $this->Bookmarks
            ->find('all')
            ->contain([
                'Users', 'Tags',
            ]);

        $collection = $bookmarks->extract('title');
        debug("Extract:title");
        debug($collection);
        debug($collection->toArray());

        echo '----------------------------------------------------------<br />';

        $collection = $bookmarks->extract(function ($bookmark) {
            return $bookmark->user->id . ', ' . $bookmark->user->name;
        });
        debug("Extract:callback");
        debug($collection);
        debug($collection->toArray());

        echo '----------------------------------------------------------<br />';

        $collection = $bookmarks->filter(function ($bookmark, $key) {
            return $bookmark->user->id === 1;
        });
        debug("Filter:callback");
        debug($collection);
        debug($collection->toArray());

        echo '----------------------------------------------------------<br />';

        $collection = $bookmarks->reject(function ($bookmark, $key) {
            return $bookmark->user->id === 1;
        });
        debug("Reject:callback");
        debug($collection);
        debug($collection->toArray());

        echo '----------------------------------------------------------<br />';

        $boolResult = $bookmarks->every(function ($bookmark, $key) {
            return $bookmark->user->id === 1;
        });
        debug("Every:callback");
        debug($boolResult);

        echo '----------------------------------------------------------<br />';

        $boolResult = $bookmarks->some(function ($bookmark, $key) {
            return $bookmark->user->id === 1;
        });
        debug("Some:callback");
        debug($boolResult);

        echo '----------------------------------------------------------<br />';

        $minResult = $bookmarks->min(function ($bookmark) {
            return count($bookmark->tags);
        });
        debug("Min:callback");
        debug($minResult);

        echo '----------------------------------------------------------<br />';

        $maxResult = $bookmarks->max(function ($bookmark) {
            return count($bookmark->tags);
        });
        debug("Max:callback");
        debug($maxResult);

        echo '----------------------------------------------------------<br />';

        $countByResult = $bookmarks->countBy(function ($bookmark) {
            return (count($bookmark->tags) == 1) ? 'One Tag' : 'More Than One Tag';
        });
        debug("CountBy:callback");
        debug($countByResult);
        debug($countByResult->toArray());
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
            $bookmark = $this->Bookmarks->patchEntity($bookmark, $this->request->getData()); // Dán (patch) data từ form vô new instance đó
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
