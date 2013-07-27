<?php
App::uses('AppController', 'Controller');
/**
 * Quizzes Controller
 *
 * @property Quiz $Quiz
 */
class QuizzesController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Quiz->recursive = 0;
		$this->set('quizzes', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Quiz->id = $id;
		if (!$this->Quiz->exists()) {
			throw new NotFoundException(__('Invalid quiz'));
		}
		$this->set('quiz', $this->Quiz->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Quiz->create();
			if ($this->Quiz->save($this->request->data)) {
				$this->Session->setFlash(__('The quiz has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The quiz could not be saved. Please, try again.'));
			}
		}
		$users = $this->Quiz->User->find('list');
		$categories = $this->Quiz->Category->find('list');
		$this->set(compact('users', 'categories'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Quiz->id = $id;
		if (!$this->Quiz->exists()) {
			throw new NotFoundException(__('Invalid quiz'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Quiz->save($this->request->data)) {
				$this->Session->setFlash(__('The quiz has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The quiz could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Quiz->read(null, $id);
		}
		$users = $this->Quiz->User->find('list');
		$categories = $this->Quiz->Category->find('list');
		$this->set(compact('users', 'categories'));
	}

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Quiz->id = $id;
		if (!$this->Quiz->exists()) {
			throw new NotFoundException(__('Invalid quiz'));
		}
		if ($this->Quiz->delete()) {
			$this->Session->setFlash(__('Quiz deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Quiz was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
