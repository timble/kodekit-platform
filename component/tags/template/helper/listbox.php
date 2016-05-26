<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-tags for the canonical source repository
 */

namespace Kodekit\Component\Tags;

use Kodekit\Library;

/**
 * Listbox Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Component\Tags
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function tags($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'autocomplete' => true,
            'autocreate'   => true,
            'component'    => $this->getIdentifier()->package,
            'entity'   => null,
            'name'     => 'tags',
            'value'    => 'title',
            'prompt'   => false,
            'deselect' => false,
            'attribs'  => array(
                'multiple' => true
            ),
        ))->append(array(
            'model'  => $this->getObject('com:tags.model.tags', array('table' => $config->component.'_tags')),
            'options' => array(
                'tokenSeparators' => array(',', ' '),
                'tags'            => $config->autocreate ? 'true' : 'false'
            ),
        ));

        //Set the selected tags
        if ($config->entity instanceof Library\ModelEntityInterface && $config->entity->isTaggable())
        {
            $config->append(array(
                'selected' => $config->entity->getTags(),
            ));
        }

        //Set the autocompplete url
        if ($config->autocomplete)
        {
            $parts = array(
                'component' => $config->component,
                'view'      => 'tags',
            );

            if ($config->filter) {
                $parts = array_merge($parts, Library\ObjectConfig::unbox($config->filter));
            }

            $config->url = $this->getObject('lib:dispatcher.router.route')->setQuery($parts);
        }

        //Do not allow to override label and sort
        $config->label = 'title';
        $config->sort  = 'title';

        return parent::render($config);
    }
}