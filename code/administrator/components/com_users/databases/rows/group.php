<?php

class ComUsersDatabaseRowGroup extends KDatabaseRowDefault
{   
    public function save() {
    	
    	$result = parent::save();
    
    	if($this->users) {

	    	// Save selected users to 'groups_users'
	    	foreach ($this->users as $key => $value) {
	    		$group_user = $this->getService('com://admin/users.database.row.groups_users');
	    		
	    		$group_user->users_group_id	= $this->id;
	    		$group_user->users_user_id 	= $value;
	    		
	    		if(!$group_user->load()) {
	    			$group_user->save();
	    		}
	    	}
	    	
	    	// Get all 'groups_users' records for the selected group
	    	foreach ($this->getService('com://admin/users.model.groups_users')->users_group_id($this->id)->getList() as $key => $value) {		
	    		
	    		// Remove all users that are no longer selected
	    		if (!in_array($value->users_user_id, $this->users)) {	    			    
    			    $group_user = $this->getService('com://admin/users.database.row.groups_users');
    			    
    			    $group_user->users_group_id	= $this->id;
    			    $group_user->users_user_id 	= $value->users_user_id;
    			    
    			    if($group_user->load()) {
    			    	$group_user->delete();
    			    }
	    		}
	    	}
    	}
       
        return $result;
    }
    
    public function delete()
    {   	    	
    	// Remove records from groups_users
    	foreach ($this->getService('com://admin/users.model.groups_users')->users_group_id($this->id)->getList() as $value) {		
    		$group_user = $this->getService('com://admin/users.database.row.groups_users');
    		
    		$group_user->users_group_id	= $this->id;
    		$group_user->users_user_id 	= $value->users_user_id;
    		
    		if($group_user->load()) {
    			$group_user->delete();
    		}
    	}
    		
    	return parent::delete();
    }
}