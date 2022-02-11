<?php


namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;

class SuperAdminsController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\ModelHasRoles');
		$this->xPanel->setRoute(admin_uri('super-admins'));
		$this->xPanel->setEntityNameStrings('Super Admins', 'Super Admins');
// 		$this->xPanel->enableReorder('name', 1);
// 		$this->xPanel->enableDetailsRow();
// 		$this->xPanel->allowAccess(['reorder', 'details_row']);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('model_id', 'DESC');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		$this->xPanel->removeButton('create');
		$this->xPanel->removeButton('update');

// 		$this->xPanel->addButtonFromModelFunction('top', 'bulk_send_invoice_email_btn', 'bulkSendInvoiceEmailBtn', 'end');

		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
        // COLUMNS
		$this->xPanel->addColumn([
			'name'  => 'model_id',
			'label' => '',
			'type'  => 'checkbox',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
            'label' => 'User name',
			'name'  => 'name',
		    'type'          => 'model_function',
		    'function_name' => 'getRoleUserName',
		]);
		$this->xPanel->addColumn([
            'label' => 'Email',
			'name'  => 'email',
		    'type'          => 'model_function',
		    'function_name' => 'getRoleUserEmail',
        ]);
        
       
    }
}
