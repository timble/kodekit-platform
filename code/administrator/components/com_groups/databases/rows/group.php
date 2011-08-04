<?php
class ComGroupsDatabaseRowGroup extends ComGroupsDatabaseRowNode
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
            'left_column'   => 'lft',
		    'right_column'  => 'rgt',
		    'parent_column' => 'parent_id'
		));
		
		parent::_initialize($config);
	}
	
	public function save()
	{
		if(!$this->_new && $this->id <= 30) {
			$this->setStatus(KDatabase::STATUS_FAILED);
            $this->setStatusMessage(JText::_('Changes are not allowed to core groups!'));

            return false;
		}
        
        if($this->parent_id > 30) {
            $this->setStatus(KDatabase::STATUS_FAILED);
            $this->setStatusMessage(JText::_('Only core groups can be selected as parent group!'));

            return false;
        }
		
		if($this->_new || $this->parent_id != $this->target_id) {
            $this->_data['tree_location'] = 'lastchild';
		}
		
		if(isset($this->_modified['name'])) {
			$this->value = $this->name;
		}
		
		return parent::save();
	}
	
	public function delete()
	{
	   if($this->id <= 30) {
            $this->setStatus(KDatabase::STATUS_FAILED);
            $this->setStatusMessage(JText::_('Changes are not allowed to core groups!'));

            return false;
        }
        
        return parent::delete();
	}
}