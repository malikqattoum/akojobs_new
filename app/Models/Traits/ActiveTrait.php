<?php


namespace App\Models\Traits;
use App\Models\User;
use App\Models\PostPackage;

trait ActiveTrait
{
    public function getActiveHtml()
    {
        if($this->getTable() == "home_sections")
            return;
        if (!isset($this->active)) return false;
        return ajaxCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'active', $this->active);
    }

    public function getPaidHtml()
    {
        if($this->getTable() == "home_sections")
            return;
        if (!isset($this->paid_status)) return false;
        
        return ajaxCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'paid_status', $this->paid_status);
    }

    
	public function getPackageName()
	{
		return PostPackage::findOrFail($this->package_id)->name;
	}

	public function getUserName()
	{
		return User::findOrFail($this->user_id)->name;
    }
    
    public function getUserEmail()
	{
		return User::findOrFail($this->user_id)->email;
    }
    
    public function getRoleUserEmail()
    {
        $out = '';
	    if (!empty($this->model_id)) {
	        $out = User::where('id',$this->model_id)->first();
            if(!empty($out))
    	       return $out->email;
	        return $out;
	    } else {
	        return "No Email";
	    }
                
        return $out;
    }

    public function getRoleUserName()
    {
        $out = '';
	    if (!empty($this->model_id)) {
	        $out = User::where('id',$this->model_id)->first();
            if(!empty($out))
    	        return $out->name;
	        return $out;
	    } else {
	        return "No Name";
	    }
                
        return $out;
    }
}