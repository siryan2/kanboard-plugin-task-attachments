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
     * Executed after the task is saved
     *
     * @param array   $project
     * @param array   $values
     * @param integer $task_id
     */
    protected function afterSave(array $project, array &$values, $task_id) {
        $hasAttachment = false;

        if(isset($_FILES['files'])) {
            $files = $_FILES['files'];
            $hasAttachment = true;
        };
        echo $hasAttachment;
        print_r('afterSave');
        exit;

        if (isset($values['duplicate_multiple_projects']) && $values['duplicate_multiple_projects'] == 1) {
            $this->chooseProjects($project, $task_id);
        } elseif (isset($values['another_task']) && $values['another_task'] == 1) {
            $this->show(array(
                'owner_id' => $values['owner_id'],
                'color_id' => $values['color_id'],
                'category_id' => isset($values['category_id']) ? $values['category_id'] : 0,
                'column_id' => $values['column_id'],
                'swimlane_id' => isset($values['swimlane_id']) ? $values['swimlane_id'] : 0,
                'another_task' => 1,
            ));
        } else {
            $this->response->redirect($this->helper->url->to('BoardViewController', 'show', array('project_id' => $project['id'])), true);
        }
    }

}
