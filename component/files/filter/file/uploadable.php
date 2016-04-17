<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * File Uploadable Filter
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class FilterFileUploadable extends Library\FilterChain
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addFilter($this->getObject('com:files.filter.file.name'), self::PRIORITY_HIGH);

        //$this->addFilter($this->getObject('com:files.filter.file.extension'));
        //$this->addFilter($this->getObject('com:files.filter.file.mimetype'));
        $this->addFilter($this->getObject('com:files.filter.file.size'));
    }
}
