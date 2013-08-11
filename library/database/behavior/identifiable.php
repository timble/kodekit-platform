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
 * Database Identifiable Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Database
 */
class DatabaseBehaviorIdentifiable extends DatabaseBehaviorAbstract
{
    /**
     * Set to true if uuid should be auto-generated
     *
     * @var boolean
     * @see _afterTableSelect()
     */
    protected $_auto_generate;

    /**
     * Constructor.
     *
     * @param   object  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        foreach ($config as $key => $value)
        {
            if (property_exists($this, '_' . $key)) {
                $this->{'_' . $key} = $value;
            }
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param     object     An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'auto_generate' => true,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the methods that are available for mixin based
     *
     * This function conditionally mixes of the behavior. Only if the mixer
     * has a 'uuid' property the behavior will be mixed in.
     *
     * @param ObjectMixable $mixer The mixer requesting the mixable methods.
     * @return array An array of methods
     */
    public function getMixableMethods(ObjectMixable $mixer = null)
    {
        $methods = array();

        if($mixer instanceof DatabaseRowInterface && $mixer->has('uuid')) {
            $methods = parent::getMixableMethods($mixer);
        }

        return $methods;
    }

    /**
     * Auto generated the uuid
     *
     * If the row exists and does not have a valid 'uuid' value auto generate it.
     *
     * Requires an 'uuid' column, if the column type is char the uuid will be a string, if the column type is binary a
     * hex value will be returned.
     *
     * @return void
     */
    protected function _afterTableSelect(CommandContext $context)
    {
        if($this->getMixer() instanceof DatabaseRowInterface && $this->_auto_generate && !$this->isNew())
        {
            if($this->has('uuid') && empty($this->uuid))
            {
                $hex = $this->getTable()->getColumn('uuid')->type == 'char' ? false : true;
                $this->uuid = $this->_uuid($hex);

                $this->save();
            }
        }
    }

    /**
     * Set uuid information
     *
     * Requires an 'uuid' column, if the column type is char the uuid will be a string, if the column type is binary a
     * hex value will be returned.
     *
     * @return void
     */
    protected function _beforeTableInsert(CommandContext $context)
    {
        if($this->getMixer() instanceof DatabaseRowInterface)
        {
            if($this->has('uuid') && empty($this->uuid))
            {
                $hex = $this->getTable()->getColumn('uuid')->type == 'char' ? false : true;
                $this->uuid = $this->_uuid($hex);
            }
        }
    }

    /**
     * Generates a Universally Unique Identifier, version 4.
     *
     * This function generates a truly random UUID.
     *
     * @param boolean    If TRUE return the uuid in hex format, otherwise as a string
     * @see http://tools.ietf.org/html/rfc4122#section-4.4
     * @see http://en.wikipedia.org/wiki/UUID
     * @return string A UUID, made up of 36 characters or 16 hex digits.
     */
    protected function _uuid($hex = false)
    {
        $pr_bits = false;

        $fp = @fopen('/dev/urandom', 'rb');
        if ($fp !== false) {
            $pr_bits = @fread($fp, 16);
            @fclose($fp);
        }

        // If /dev/urandom isn't available (eg: in non-unix systems), use mt_rand().
        if (empty($pr_bits))
        {
            $pr_bits = "";
            for ($cnt = 0; $cnt < 16; $cnt++) {
                $pr_bits .= chr(mt_rand(0, 255));
            }
        }

        $time_low = bin2hex(substr($pr_bits, 0, 4));
        $time_mid = bin2hex(substr($pr_bits, 4, 2));
        $time_hi_and_version = bin2hex(substr($pr_bits, 6, 2));
        $clock_seq_hi_and_reserved = bin2hex(substr($pr_bits, 8, 2));
        $node = bin2hex(substr($pr_bits, 10, 6));

        /**
         * Set the four most significant bits (bits 12 through 15) of the
         * time_hi_and_version field to the 4-bit version number from
         * Section 4.1.3.
         * @see http://tools.ietf.org/html/rfc4122#section-4.1.3
         */
        $time_hi_and_version = hexdec($time_hi_and_version);
        $time_hi_and_version = $time_hi_and_version >> 4;
        $time_hi_and_version = $time_hi_and_version | 0x4000;

        /**
         * Set the two most significant bits (bits 6 and 7) of the
         * clock_seq_hi_and_reserved to zero and one, respectively.
         */
        $clock_seq_hi_and_reserved = hexdec($clock_seq_hi_and_reserved);
        $clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved >> 2;
        $clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved | 0x8000;

        //Either return as hex or as string
        $format = $hex ? '%08s%04s%04x%04x%012s' : '%08s-%04s-%04x-%04x-%012s';

        return sprintf($format, $time_low, $time_mid, $time_hi_and_version, $clock_seq_hi_and_reserved, $node);
    }
}