<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Http Dispatcher
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Component\Files
 */
class FilesDispatcherHttp extends Library\DispatcherHttp
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        // Return JSON response when possible
        $this->addCommandCallback('after.post' , '_afterPost');

        // Return correct status code for plupload
        $this->addCommandCallback('before.send', '_beforeSend');
    }

    protected function _afterPost(Library\DispatcherContextInterface $context)
    {
        if ($context->action !== 'delete' && $this->getRequest()->getFormat() === 'json') {
            $this->getController()->execute('render', $context);
        }
    }

    /**
     * Plupload do not pass the error to our application if the status code is not 200
     */
    protected function _beforeSend(Library\DispatcherContextInterface $context)
    {
        if ($context->request->getFormat() == 'json' && $context->request->query->get('plupload', 'int')) {
            $context->response->setStatus('200');
        }
    }
}