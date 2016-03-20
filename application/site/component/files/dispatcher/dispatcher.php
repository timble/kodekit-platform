<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Files;

use Kodekit\Library;

/**
 * Http Dispatcher
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Platform\Files
 */
class Dispatcher extends Library\Dispatcher
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        // Return JSON response when possible
        $this->addCommandCallback('after.post' , '_afterPost');

        // Return correct status code for plupload
        $this->addCommandCallback('before.send', '_beforeSend');
    }

    public function canDispatch()
    {
        return true;
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