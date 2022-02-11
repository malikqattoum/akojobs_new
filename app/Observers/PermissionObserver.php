<?php


namespace App\Observer;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;
use Prologue\Alerts\Facades\Alert;

class PermissionObserver
{
	/**
	 * Listen to the Entry deleting event.
	 *
	 * @param  Permission $permission
	 * @return bool
	 */
	public function deleting(Permission $permission)
	{
		// Check if default permission exist, to prevent recursion of the deletion.
		if (Permission::checkDefaultPermissions()) {
			// Don't delete Super Admin default permissions
			$superAdminPermissions = Permission::getSuperAdminPermissions();
			$superAdminPermissions = collect($superAdminPermissions)->map(function ($item, $key) {
				return strtolower($item);
			})->toArray();
			if (in_array(strtolower($permission->name), $superAdminPermissions)) {
				Alert::warning(trans('admin::messages.You cannot delete a Super Admin default permission.'))->flash();
				
				// Since Laravel detach all pivot entries before starting deletion,
				// Re-assign the permission to the Super Admin role.
				$permission->assignRole(Role::getSuperAdminRole());
				
				return false;
			}
			
			// Don't delete Staff default permissions
			$adminPermissions = Permission::getStaffPermissions();
			$adminPermissions = collect($adminPermissions)->map(function ($item, $key) {
				return strtolower($item);
			})->toArray();
			if (in_array(strtolower($permission->name), $adminPermissions)) {
				Alert::warning(trans('admin::messages.You cannot delete a staff default permission.'))->flash();
				
				// Optional
				$permission->assignRole(Role::getSuperAdminRole());
				
				return false;
			}
		}
	}
	
    /**
     * Listen to the Entry saved event.
     *
     * @param  Permission $permission
     * @return void
     */
    public function saved(Permission $permission)
    {
        // Removing Entries from the Cache
        $this->clearCache($permission);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  Permission $permission
     * @return void
     */
    public function deleted(Permission $permission)
    {
        // Removing Entries from the Cache
        $this->clearCache($permission);
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $permission
     */
    private function clearCache($permission)
    {
        Cache::flush();
    }
}
