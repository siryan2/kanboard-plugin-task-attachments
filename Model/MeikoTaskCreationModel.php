<?php

namespace Kanboard\Plugin\TaskAttachments\Model;

use Kanboard\Model\TaskCreationModel;

/**
 * Meiko Task Creation Model
 *
 * @package  Kanboard\Plugin\TaskAttachments
 * @author   Yannick Herzog
 */
class MeikoTaskCreationModel extends TaskCreationModel {

	/**
	 * Nach dem Speichern eines Tasks wird der Anhang gespeichert, sofern vorhanden.
	 *
	 * @todo Das Speichern eines Anhangs sollte nicht im Model, sondern in einem Controller passieren.
	 *
	 * @param  array  $values [description]
	 * @return [int]  			Task id
	 */
	public function create(array $values){
		$hasAttachment = false;

		if(isset($_FILES['files'])) {
			$files = $_FILES['files'];
			$hasAttachment = true;
		};

		/**
		 * Hole task_id nachdem die Aufgabe erstellt wurde
		 */
		$task_id = parent::create($values);

		/**
		 * Speichere Anhang
		 */
		if($hasAttachment) {
			$result = $this->taskFileModel->uploadFiles($task_id, $files, false);

			if (! $result) {
                $this->flash->failure(t('Unable to upload files, check the permissions of your data folder.'));
            }
		}

		return $task_id;
	}

}
