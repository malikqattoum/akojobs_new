<?php


namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\PackageReqRequest as StoreRequest;
use App\Http\Requests\Admin\PackageReqRequest as UpdateRequest;
use App\Models\User;
use App\Models\PostPackage;

class PackageRequestController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\PackageRequest');
		$this->xPanel->setRoute(admin_uri('packages-requests'));
		$this->xPanel->setEntityNameStrings(trans('admin::messages.package'), 'Packages Requests');
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->enableDetailsRow();
		$this->xPanel->allowAccess(['reorder', 'details_row']);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('id', 'ASC');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_send_invoice_email_btn', 'bulkSendInvoiceEmailBtn', 'end');

		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
        // COLUMNS
		$this->xPanel->addColumn([
			'name'  => 'id',
			'label' => '',
			'type'  => 'checkbox',
			'orderable' => false,
		]);
		$this->xPanel->addColumn([
            'label' => 'User name',
			'name'  => 'user_id',
            'type'          => 'model_function',
            'function_name' => 'getUserName',
		]);
		$this->xPanel->addColumn([
            'label' => 'Email',
			'name'  => 'email',
            'type'          => 'model_function',
            'function_name' => 'getUserEmail',
        ]);
        $this->xPanel->addColumn([
            'label' => 'Package',
			'name'  => 'package_id',
            'type'          => 'model_function',
            'function_name' => 'getPackageName',
		]);
        $this->xPanel->addColumn([
			'name'          => 'valid_jobs_num',
			'label'         => 'Valid jobs number',
		]);
        $this->xPanel->addColumn([
			'name'  => 'date',
			'label' => 'Request date',
        ]);
        $this->xPanel->addColumn([
			'name'  => 'approve_date',
			'label' => 'Approve date',
        ]);
        $this->xPanel->addColumn([
			'name'  => 'end_date',
			'label' => 'End date',
		]);
		$this->xPanel->addColumn([
			'name'          => 'paid_status',
			'label'         => 'Paid Status',
			'type'          => 'model_function',
			'function_name' => 'getPaidHtml',
			'on_display'    => 'checkbox',
		]);
		$this->xPanel->addColumn([
			'name'          => 'active',
			'label'         => trans("admin::messages.Active"),
			'type'          => 'model_function',
			'function_name' => 'getActiveHtml',
			'on_display'    => 'checkbox',
		]);
		
        // FIELDS
        $this->xPanel->addField([
			'name'       => 'user_id',
			'label'      => 'Package user',
            'type'              => 'select2_from_array',
            'options'           => $this->getAllUsers(),
            'allows_null'       => false,
			'attributes' => [
				'placeholder' => 'Package',
			],
		]);
        $this->xPanel->addField([
			'name'       => 'package_id',
			'label'      => 'Package',
            'type'              => 'select2_from_array',
            'options'           => $this->getPackagesList(),
            'allows_null'       => false,
			'attributes' => [
				'placeholder' => 'Package',
			],
		]);
        $this->xPanel->addField([
			'name'              => 'approve_date',
			'label'             => "Approve date",
			'type'  => 'text',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
				'style' => 'margin-top: 20px;',
			],
			'attributes' => [
				'placeholder' => 'YYYY-MM-DD',
				'required'	=> true,
				'pattern'	=> '^\d{4}(-)(((0)[0-9])|((1)[0-2]))(-)([0-2][0-9]|(3)[0-1])$',
				'title'		=> 'Enter a date in this format YYYY/MM/DD',
			],
        ]);
        $this->xPanel->addField([
			'name'              => 'end_date',
			'label'             => "End date",
			'type'  => 'text',
			'attributes' => [
				'placeholder' => 'YYYY-MM-DD',
				'required'	=> true,
				'pattern'	=> '^\d{4}(-)(((0)[0-9])|((1)[0-2]))(-)([0-2][0-9]|(3)[0-1])$',
				'title'		=> 'Enter a date in this format YYYY/MM/DD',
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
				'style' => 'margin-top: 20px;',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'valid_jobs_num',
			'label'             => "Valid jobs number",
			'type'  => 'number',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-12',
				'style' => 'margin-top: 20px;',
			],
        ]);
        $this->xPanel->addField([
			'name'              => 'active',
			'label'             => trans("admin::messages.Active"),
			'type'              => 'checkbox',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
				'style' => 'margin-top: 20px;',
			],
		]);
        $this->xPanel->addField([
			'name'              => 'paid_status',
			'label'             => "Paid Status",
			'type'              => 'checkbox',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
				'style' => 'margin-top: 20px;',
			],
        ]);
    }

	public function getPackagesList()
	{
		return PostPackage::where('translation_lang', 'en')->pluck('name', 'id');
	}

	public function getAllUsers()
	{
		return User::all()->pluck('email', 'id');
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
