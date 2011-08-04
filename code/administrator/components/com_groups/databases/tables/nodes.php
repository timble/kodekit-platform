<?php
class ComGroupsDatabaseTableNodes extends KDatabaseTableAbstract
{
        public function lock()
        {
            // TODO: Improve locking of tables when Nooku implements it
        $this->getDatabase()->execute('LOCK TABLES `'.$this->getPrefixedBase().'` WRITE, `'.$this->getPrefixedName().'` READ');
        }
        
        public function unlock()
        {
        $this->getDatabase()->execute('UNLOCK TABLES');
        }

        /**
     * A Temporary function to get the table with temporary prefix. 
     *      TODO: It will be unnecessary when NFW's query builder for UPDATE and DELETE is done
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
        public function getPrefixedBase()
    {
        return '#__'.$this->getBase();
    }
    
    public function getPrefixedName()
    {
        return '#__'.$this->getName();
    }
}