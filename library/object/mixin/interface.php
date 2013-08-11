<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Mixes Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
interface ObjectMixinInterface extends ObjectHandlable
{
    /**
     * Get the methods that are available for mixin.
     *
     * Only public methods can be mixed
     *
     * @param ObjectMixable $mixer The mixer requesting the mixable methods.
     * @return array An array of public methods
     */
    public function getMixableMethods(ObjectMixable $mixer = null);

    /**
     * Mixin Notifier
     *
     * This function is called when the mixin is being mixed. It will get the mixer passed in.
     *
     * @param ObjectMixable $mixer The mixer object
     * @return void
     */
    public function onMixin(ObjectMixable $mixer);

	/**
     * Get the mixer object
     *
     * @return ObjectMixable	The mixer object
     */
    public function getMixer();

    /**
     * Set the mixer object
     *
     * @param ObjectMixable $mixer The mixer object
     * @return ObjectMixinInterface
     */
    public function setMixer(ObjectMixable $mixer);
}