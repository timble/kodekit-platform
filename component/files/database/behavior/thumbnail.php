<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * File Thumbnail Behavior
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Koowa\Component\Files
 */
class DatabaseBehaviorThumbnail extends Library\DatabaseBehaviorAbstract
{
    /**
     * Constructor.
     *
     * @param Library\ObjectConfig $config An optional Library\ObjectConfig object with configuration options.
     */
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addCommandCallback('after.save', 'saveThumbnail');
        $this->addCommandCallback('after.delete', 'deleteThumbnail');
    }

    public function saveThumbnail()
    {
        $result               = null;
        $available_extensions = array('jpg', 'jpeg', 'gif', 'png');

        if ($this->isImage() && $this->getContainer()->getParameters()->thumbnails && in_array(strtolower($this->extension), $available_extensions)) {
            $parameters = $this->getContainer()->getParameters();
            $size       = isset($parameters['thumbnail_size']) ? $parameters['thumbnail_size'] : array();

            $thumb         = $this->getObject('com:files.database.row.thumbnail', array('size' => $size));
            $thumb->source = $this;

            $result = $thumb->save();
        }

        return $result;
    }

    public function deleteThumbnail()
    {
        $thumb = $this->getObject('com:files.model.thumbnails')
            ->container($this->container)
            ->folder($this->folder)
            ->filename($this->name)
            ->fetch();

        $result = $thumb->delete();

        return $result;
    }
}