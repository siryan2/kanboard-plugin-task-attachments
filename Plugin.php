<?php

namespace Kanboard\Plugin\TaskAttachments;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\TaskAttachments\Controller\MeikoTaskCreationController;
use Kanboard\Plugin\TaskAttachments\Model\MeikoTaskCreationModel;


class Plugin extends Base
{
    public function initialize() {
        $container = $this->container;

        $this->container['taskCreationModel'] = $this->container->factory(function ($c) {
            return new MeikoTaskCreationModel($c);
        });

        $this->container['taskCreationController'] = $this->container->factory(function ($c) {
            return new MeikoTaskCreationController($c);
        });

        $this->hook->on('template:layout:css', array('template' => 'plugins/TaskAttachments/Asset/css/taskAttachments.css'));
        $this->hook->on('template:layout:js', array('template' => 'plugins/TaskAttachments/Asset/js/taskAttachments.js'));

        $this->template->hook->attach('template:task:form:second-column', 'TaskAttachments:task/second-column', array(
            'task_id' => $_GET['task_id'],
            'project_id' => $_GET['project_id'],
        ));
    }

    public function onStartup() {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getClasses() {
        return array(
            'Plugin\TaskAttachments\Model' => array(
                'MeikoTaskCreationModel',
            ),
            'Plugin\TaskAttachments\Controller' => array(
                'MeikoTaskCreationController',
            )
        );
    }

    public function getPluginName() {
        return 'TaskAttachments';
    }

    public function getPluginDescription() {
        return t('This plugin give the possibility to add an attachment if user create a new task.');
    }

    public function getPluginAuthor() {
        return 'Yannick Herzog';
    }

    public function getPluginVersion() {
        return '0.2.0';
    }

    public function getPluginHomepage() {
        return 'https://github.com/siryan2/kanboard-plugin-task-attachments';
    }
}
