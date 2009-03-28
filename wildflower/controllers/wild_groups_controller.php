<?php
class WildGroupsController extends AppController {

	var $name = 'WildGroups';
	var $helpers = array('Html', 'Form');

	function index() {
		$this->WildGroup->recursive = 0;
		$this->set('wildGroups', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid WildGroup.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('wildGroup', $this->WildGroup->read(null, $id));
	}

	function add() {
		if (!empty($this->data)) {
			$this->WildGroup->create();
			if ($this->WildGroup->save($this->data)) {
				$this->Session->setFlash(__('The WildGroup has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The WildGroup could not be saved. Please, try again.', true));
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid WildGroup', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->WildGroup->save($this->data)) {
				$this->Session->setFlash(__('The WildGroup has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The WildGroup could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->WildGroup->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for WildGroup', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->WildGroup->del($id)) {
			$this->Session->setFlash(__('WildGroup deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}


	function wf_index() {
		$this->WildGroup->recursive = 0;
		$this->set('wildGroups', $this->paginate());
	}

	function wf_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid WildGroup.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('wildGroup', $this->WildGroup->read(null, $id));
	}

	function wf_add() {
		if (!empty($this->data)) {
			$this->WildGroup->create();
			if ($this->WildGroup->save($this->data)) {
				$this->Session->setFlash(__('The WildGroup has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The WildGroup could not be saved. Please, try again.', true));
			}
		}
	}

	function wf_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid WildGroup', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->WildGroup->save($this->data)) {
				$this->Session->setFlash(__('The WildGroup has been saved', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The WildGroup could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->WildGroup->read(null, $id);
		}
	}

	function wf_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for WildGroup', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->WildGroup->del($id)) {
			$this->Session->setFlash(__('WildGroup deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

}
?>