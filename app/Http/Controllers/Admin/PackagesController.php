<?php


namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\PostPackageRequest as StoreRequest;
use App\Http\Requests\Admin\PostPackageRequest as UpdateRequest;

class PackagesController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\PostPackage');
		$this->xPanel->setRoute(admin_uri('post_packages'));
		$this->xPanel->setEntityNameStrings(trans('admin::messages.package'), trans('admin::messages.packages'));
		$this->xPanel->enableReorder('name', 1);
		$this->xPanel->enableDetailsRow();
		$this->xPanel->allowAccess(['reorder', 'details_row']);
		if (!request()->input('order')) {
			$this->xPanel->orderBy('id', 'ASC');
		}
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		
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
			'name'  => 'name',
			'label' => trans("admin::messages.Name"),
        ]);
        $this->xPanel->addColumn([
			'name'  => 'description',
			'label' => 'Description',
		]);
		$this->xPanel->addColumn([
			'name'  => 'post_num',
			'label' => 'Post Numbers',
        ]);
        $this->xPanel->addColumn([
			'name'  => 'period',
			'label' => 'Period in days',
        ]);
        $this->xPanel->addColumn([
			'name'  => 'price',
			'label' => trans("admin::messages.Price"),
		]);
        $this->xPanel->addColumn([
			'name'  => 'currency',
			'label' => 'Currency',
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
			'name'              => 'name',
			'label'             => trans("admin::messages.Name"),
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => trans("admin::messages.Name"),
			],
        ]);
        $this->xPanel->addField([
			'name'       => 'description',
			'label'      => trans('admin::messages.Description'),
			'type'       => 'text',
			'attributes' => [
				'placeholder' => trans('admin::messages.Description'),
			],
		]);
        $this->xPanel->addField([
			'name'              => 'post_num',
			'label'             => 'Number of allowed posts',
			'type'              => 'text',
            'placeholder'       => 'Number of allowed posts',
            'attributes'        => [
				'placeholder' => 'Ex: 3',
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'period',
			'label'             => 'Period',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => 'Period in (days) Ex:60',
			],
			'hint'              => 'Package period (the package will expire after this period)',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
        ]);
        $this->xPanel->addField([
			'name'              => 'price',
			'label'             => trans("admin::messages.Price"),
			'type'              => 'text',
            'placeholder'       => trans("admin::messages.Price"),
            'attributes'        => [
				'placeholder' => 'Ex: 99',
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
        ]);
        $this->xPanel->addField([
			'name'              => 'currency',
			'label'             => 'Currency',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => 'Ex:IQD',
			],
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'active',
			'label'             => trans("admin::messages.Active"),
			'type'              => 'checkbox',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
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
