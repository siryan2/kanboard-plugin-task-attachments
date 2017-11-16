<?php

namespace Kanboard\Plugin\TaskAttachments\Controller;

use Kanboard\Controller\TaskCreationController;

/**
 * Meiko Task Creation Controller
 *
 * @package  Kanboard\Plugin\TaskAttachments
 * @author   Yannick Herzog
 */
class MeikoTaskCreationController extends TaskCreationController {

	/**
     * Validate and save a new task
     *
     * @access public
     */
    public function save() {
    	$this->response->withStatusCode(201)->json(array('test' => 1));

        $project = $this->getProject();
        $values = $this->request->getValues();
        $values['project_id'] = $project['id'];

        list($valid, $errors) = $this->taskValidator->validateCreation($values);

        if (! $valid) {
            $this->flash->failure(t('Unable to create your task.'));
            $this->show($values, $errors);
        } else if (! $this->helper->projectRole->canCreateTaskInColumn($project['id'], $values['column_id'])) {
            $this->flash->failure(t('You cannot create tasks in this column.'));
            $this->response->redirect($this->helper->url->to('BoardViewController', 'show', array('project_id' => $project['id'])), true);
        } else {
            $task_id = $this->taskCreationModel->create($values);

			$files = $_FILES['files'];
			print_r($files);
			$result = $this->taskFileModel->uploadFiles($task_id, $files);

			$this->response
				->withStatusCode(201)
				->json($result);

			exit;
            if ($task_id > 0) {
                $this->flash->success(t('Task created successfully.'));
                $this->afterSave($project, $values, $task_id);
            } else {
                $this->flash->failure(t('Unable to create this task.'));
                $this->response->redirect($this->helper->url->to('BoardViewController', 'show', array('project_id' => $project['id'])), true);
            }
        }
    }

}
