<?php

namespace Kanboard\Plugin\TaskAttachments\Model;

use Kanboard\Model\TaskFileModel;

/**
 * Meiko File Model
 *
 * @package  Kanboard\Plugin\TaskAttachments
 * @author   Yannick Herzog
 */
class MeikoTaskFileModel extends TaskFileModel {

	/**
     * Create a file entry in the database
     *
     * @access public
     * @param  integer $foreign_key_id Foreign key
     * @param  string  $name           Filename
     * @param  string  $path           Path on the disk
     * @param  integer $size           File size
     * @param  bool    $creationEvent  Fire event
     * @return bool|integer
     */
    public function create($foreign_key_id, $name, $path, $size, $creationEvent = true) {
        $values = array(
            $this->getForeignKey() => $foreign_key_id,
            'name' => substr($name, 0, 255),
            'path' => $path,
            'is_image' => $this->isImage($name) ? 1 : 0,
            'size' => $size,
            'user_id' => $this->userSession->getId() ?: 0,
            'date' => time(),
        );

        $result = $this->db->table($this->getTable())->insert($values);

        if ($result) {
            $file_id = (int) $this->db->getLastId();
            if($creationEvent) {
            	$this->fireCreationEvent($file_id);
            }
            return $file_id;
        }

        return false;
    }

    /**
     * Upload multiple files
     *
     * @access public
     * @param  integer  $id
     * @param  array    $files
     * @param  bool     $creationEvent
     * @return bool
     */
    public function uploadFiles($id, array $files, $creationEvent = true)
    {
        try {
            if (empty($files)) {
                return false;
            }

            foreach (array_keys($files['error']) as $key) {
                $file = array(
                    'name' => $files['name'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'size' => $files['size'][$key],
                    'error' => $files['error'][$key],
                );

                $this->uploadFile($id, $file, $creationEvent);
            }

            return true;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            return false;
        }
    }

    /**
     * Upload a file
     *
     * @access public
     * @param  integer $id
     * @param  array   $file
     * @param  bool    $creationEvent
     * @throws Exception
     */
    public function uploadFile($id, array $file, $creationEvent)
    {
        if ($file['error'] == UPLOAD_ERR_OK && $file['size'] > 0) {
            $destination_filename = $this->generatePath($id, $file['name']);

            if ($this->isImage($file['name'])) {
                $this->generateThumbnailFromFile($file['tmp_name'], $destination_filename);
            }

            $this->objectStorage->moveUploadedFile($file['tmp_name'], $destination_filename);
            $this->create($id, $file['name'], $destination_filename, $file['size'], $creationEvent);
        } else {
            throw new Exception('File not uploaded: '.var_export($file['error'], true));
        }
    }

}
