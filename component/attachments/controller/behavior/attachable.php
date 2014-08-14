<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Attachments;

use Nooku\Library;

/**
 * Attachable Controller Behavior
 *
 * @author  Steven Rombauts <http://github.com/stevenrombauts>
 * @package Nooku\Component\Attachments
 */
class ControllerBehaviorAttachable extends Library\ControllerBehaviorAbstract
{
    /**
     * Attachments array coming from $_FILES
     */
    protected $_attachments = array();

    /**
     * Container to use in files
     */
    protected $_container = null;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_container = $config->container;

        $this->addCommandCallback('before.add'  , '_fetchFiles');
        $this->addCommandCallback('before.edit' , '_fetchFiles');
        $this->addCommandCallback('after.add'   , '_storeFiles');
        $this->addCommandCallback('after.edit'  , '_storeFiles');
        $this->addCommandCallback('after.delete', '_deleteFiles');
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'container'  => 'attachments-attachments',
        ));

        parent::_initialize($config);
    }

    public function getAttachments()
    {
        return $this->_attachments;
    }

    protected function _fetchFiles(Library\ControllerContextInterface $context)
    {
        $files = array();

        $attachments = $context->request->files->get('attachments', 'raw');
        if (is_array($attachments['name']))
        {
            // Why do you return such a weird array for files PHP? why?
            for ($i = 0, $n = count($attachments['name']); $i < $n; $i++)
            {
                if ($attachments['error'][$i] == UPLOAD_ERR_NO_FILE) {
                    continue;
                }

                $file = array();
                foreach (array_keys($attachments) as $key) {
                    $file[$key] = $attachments[$key][$i];
                }

                $files[] = $file;
            }

        } elseif (is_array($attachments)) {
            $files[] = $attachments;
        }

        $this->_attachments = $files;
    }

    protected function _storeFiles(Library\ControllerContextInterface $context)
    {
        if (!$context->response->isError())
        {
            foreach ($this->_attachments as $attachment) {
                $this->_storeFile($context, $attachment);
            }

            return true;
        }
    }

    protected function _storeFile(Library\ControllerContextInterface $context, $attachment)
    {
        $entity = $context->result;

        $extension  = pathinfo($attachment['name'], PATHINFO_EXTENSION);
        $name       = md5(time().mt_rand()).'.'.$extension;
        $hash       = md5_file($attachment['tmp_name']);

        // Save file
        $this->getObject('com:files.controller.file')
            ->container($this->_container)
            ->add(array(
                'file'   => $attachment['tmp_name'],
                'name'   => $name,
                'parent' => ''
            ));

        // Save attachment
        $this->getObject('com:attachments.controller.attachment')->add(array(
            'name'      => $attachment['name'],
            'path'      => $name,
            'container' => $this->_container,
            'hash'      => $hash,
            'row'       => $entity->id,
            'table'     => $entity->getTable()->getBase()
        ));

        return true;
    }

    protected function _deleteFiles(Library\ControllerContextInterface $context)
    {
        $entity = $context->result;
        $status = $context->result->getStatus();

        if($status == $entity::STATUS_DELETED || $status == 'trashed')
        {
            $id    = $entity->id;
            $table = $entity->getTable()->getBase();

            if(!empty($id) && $id != 0)
            {
                $this->getObject('com:attachments.model.attachments')
                    ->row($id)
                    ->table($table)
                    ->fetch()
                    ->delete();
            }
        }
    }
}