<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Requests\Admin\Request;
use App\Http\Requests\Admin\UserRequest as StoreRequest;
use App\Http\Requests\Admin\UserRequest as UpdateRequest;
use App\Models\Gender;
use App\Models\Permission;
use App\Models\Role;
use App\Models\UserType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Larapen\Admin\app\Http\Controllers\PanelController;

class EmployerController extends PanelController
{
	use VerificationTrait;

	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		// enable export buttons
		$this->xPanel->export_buttons = true;
		$this->xPanel->setModel('App\Models\User');
		$this->xPanel->setRoute(admin_uri('employers'));
		$this->xPanel->setEntityNameStrings("Employers", "Employers");
		if (!request()->input('order')) {
			$this->xPanel->orderBy('created_at', 'DESC');
        }
        $this->xPanel->where('user_type_id', 1);

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		//$this->xPanel->addButtonFromModelFunction('line', 'impersonate', 'impersonateBtn', 'beginning');
		$this->xPanel->removeButton('delete');
		$this->xPanel->addButtonFromModelFunction('line', 'delete', 'deleteBtn', 'end');

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_verify_btn', 'bulkVerifyBtn', 'end');		

		// Filters
		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'id',
			'type'  => 'text',
			'label' => 'ID',
		],
			false,
			function ($value) {
				$this->xPanel->addClause('where', 'id', '=', $value);
			});
		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'from_to',
			'type'  => 'date_range',
			'label' => trans('admin::messages.Date range'),
		],
			false,
			function ($value) {
				$dates = json_decode($value);
				$this->xPanel->addClause('where', 'created_at', '>=', $dates->from);
				$this->xPanel->addClause('where', 'created_at', '<=', $dates->to);
			});
		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'name',
			'type'  => 'text',
			'label' => trans('admin::messages.Name'),
		],
			false,
			function ($value) {
				$this->xPanel->addClause('where', 'name', 'LIKE', "%$value%");
			});
		// -----------------------
		// $this->xPanel->addFilter([
		// 	'name'  => 'country',
		// 	'type'  => 'select2',
		// 	'label' => trans('admin::messages.Country'),
		// ],
		// 	getCountries(),
		// 	function ($value) {
		// 		$this->xPanel->addClause('where', 'country_code', '=', $value);
		// });
		// -----------------------
		$this->xPanel->addFilter([
			'name'  => 'status',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Status'),
		], [
			1 => trans('admin::messages.Unactivated'),
			2 => trans('admin::messages.Activated'),
		], function ($value) {
			if ($value == 1) {
				$this->xPanel->addClause('where', 'verified_email', '=', 0);
				$this->xPanel->addClause('orWhere', 'verified_phone', '=', 0);
			}
			if ($value == 2) {
				$this->xPanel->addClause('where', 'verified_email', '=', 1);
				$this->xPanel->addClause('where', 'verified_phone', '=', 1);
			}
		});

		$residenceCountry = include(base_path() . '/resources/lang/en/residenceCountry.php');
		asort($residenceCountry); 
		$residenceCountryKeys = array_keys($residenceCountry);
		$residenceCountryConfig = [];
		foreach ($residenceCountryKeys as $value) {
			$residenceCountryConfig[$value] = t($value,[],'residenceCountry');
		}

		$this->xPanel->addFilter([
			'name'  => 'residence_country',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Residence Country'),
		],
		$residenceCountryConfig
		,function ($value) {
			$this->xPanel->addClause('where', 'residence_country', '=', $value);
			$this->xPanel->addClause('orWhere', 'residence_country', '=', $value);
		});
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		if (request()->segment(2) != 'account') {
			// COLUMNS
			$this->xPanel->addColumn([
				'name'  => 'id',
				'label' => '',
				'type'  => 'checkbox',
				'orderable' => false,
			]);
			$this->xPanel->addColumn([
				'name'  => 'created_at',
				'label' => trans("admin::messages.Date"),
				'type'  => 'datetime',
			]);
			$this->xPanel->addColumn([
				'name'  => 'name',
				'label' => 'Contact person name',
            ]);
            
            $this->xPanel->addColumn([
                'name'          => 'company_name',
                'label'         => 'Company name',
				'type'          => 'model_function',
				'function_name' => 'getCompanyName',
            ]);

            $this->xPanel->addColumn([
				'name'      => 'company_location',
				'label'     => "Company location",
				'type'          => 'model_function',
				'function_name' => 'getCompanyLocation',
            ]);
            
            $this->xPanel->addColumn([
				'label'         => 'contact person job title',
				'name'          => 'current_job_title',
				'type'          => 'model_function',
				'function_name' => 'getCurrentJobTitle',
			]);
            
			$this->xPanel->addColumn([
				'name'  => 'email',
				'label' => trans("admin::messages.Email"),
			]);

			$this->xPanel->addColumn([
				'label'         => trans("admin::messages.Note"),
				'name'          => 'note',
				'type'          => 'model_function',
				'function_name' => 'getNote',
			]);

			$this->xPanel->addColumn([
				'label'         => trans("admin::messages.Phone"),
				'name'          => 'phone',
				'type'          => 'model_function',
				'function_name' => 'getPhoneNumber',
			]);

			$this->xPanel->addColumn([
				'name'          => 'verified_email',
				'label'         => trans("admin::messages.Verified Email"),
				'type'          => 'model_function',
				'function_name' => 'getVerifiedEmailHtml',
			]);
			
			// FIELDS
			$emailField = [
				'name'       => 'email',
				'label'      => trans("admin::messages.Email"),
				'type'       => 'email',
				'attributes' => [
					'placeholder' => trans("admin::messages.Email"),
				],
			];
			$this->xPanel->addField($emailField + [
					'wrapperAttributes' => [
						'class' => 'form-group col-md-6',
					]
				], 'create');
			$this->xPanel->addField($emailField, 'update');
			
			$passwordField = [
				'name'       => 'password',
				'label'      => trans("admin::messages.Password"),
				'type'       => 'password',
				'attributes' => [
					'placeholder' => trans("admin::messages.Password"),
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			];
			$this->xPanel->addField($passwordField, 'create');
			
			$this->xPanel->addField([
				'label'             => trans("admin::messages.Gender"),
				'name'              => 'gender_id',
				'type'              => 'select2_from_array',
				'options'           => $this->gender(),
				'allows_null'       => false,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'name',
				'label'             => trans("admin::messages.Name"),
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => trans("admin::messages.Name"),
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'phone',
				'label'             => trans("admin::messages.Phone"),
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => trans("admin::messages.Phone"),
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'phone_hidden',
				'label'             => trans("admin::messages.Phone hidden"),
				'type'              => 'checkbox',
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
					'style' => 'margin-top: 20px;',
				],
			]);

			$this->xPanel->addField([
				'name'              => 'note',
				'label'             => trans("admin::messages.Note"),
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => trans("admin::messages.Note"),
				],
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'verified_email',
				'label'             => trans("admin::messages.Verified Email"),
				'type'              => 'checkbox',
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
					'style' => 'margin-top: 20px;',
				],
			]);
			// $this->xPanel->addField([
			// 	'name'              => 'verified_phone',
			// 	'label'             => trans("admin::messages.Verified Phone"),
			// 	'type'              => 'checkbox',
			// 	'wrapperAttributes' => [
			// 		'class' => 'form-group col-md-6',
			// 		'style' => 'margin-top: 20px;',
			// 	],
			// ]);
			$this->xPanel->addField([
				'name'              => 'blocked',
				'label'             => trans("admin::messages.Blocked"),
				'type'              => 'checkbox',
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
					'style' => 'margin-top: 20px;',
				],
			]);
			$entity = $this->xPanel->getModel()->find(request()->segment(3));
			if (!empty($entity)) {
				$ipLink = config('larapen.core.ipLinkBase') . $entity->ip_addr;
				$this->xPanel->addField([
					'name'  => 'ip_addr',
					'type'  => 'custom_html',
					'value' => '<h5><strong>IP:</strong> <a href="' . $ipLink . '" target="_blank">' . $entity->ip_addr . '</a></h5>',
				], 'update');
			}
		}
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function account()
	{
		// FIELDS
		$this->xPanel->addField([
			'label'             => trans("admin::messages.Gender"),
			'name'              => 'gender_id',
			'type'              => 'select2_from_array',
			'options'           => $this->gender(),
			'allows_null'       => false,
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'name',
			'label'             => trans("admin::messages.Name"),
			'type'              => 'text',
			'placeholder'       => trans("admin::messages.Name"),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'email',
			'label'             => trans("admin::messages.Email"),
			'type'              => 'email',
			'placeholder'       => trans("admin::messages.Email"),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'password',
			'label'             => trans("admin::messages.Password"),
			'type'              => 'password',
			'placeholder'       => trans("admin::messages.Password"),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);
		$this->xPanel->addField([
			'name'              => 'phone',
			'label'             => trans("admin::messages.Phone"),
			'type'              => 'text',
			'placeholder'       => trans("admin::messages.Phone"),
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);

		$this->xPanel->addField([
			'label'             => trans("admin::messages.Country"),
			'name'              => 'country_code',
			'model'             => 'App\Models\Country',
			'entity'            => 'country',
			'attribute'         => 'asciiname',
			'type'              => 'select2',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);


		$this->xPanel->addField([
			'name'              => 'phone_hidden',
			'label'             => "Phone hidden",
			'type'              => 'checkbox',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
				'style' => 'margin-top: 20px;',
			],
		]);

		$this->xPanel->addField([
			'name'              => 'is_admin',
			'label'             => "Has All Access",
			'type'              => 'checkbox',
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
				'style' => 'margin-top: 20px;',
			],
		]);
		
		// Get logged user
		if (auth()->check()) {
			return $this->edit(auth()->user()->id);
		} else {
			abort(403, 'Not allowed.');
		}
	}
	
	public function store(StoreRequest $request)
	{
		$this->handleInput($request);
		
		return parent::storeCrud();
	}

	public function addNote(Request $request)
	{
		$request->validate([
			'note' => 'required',
			'user_id' =>'required',
		]);
		
		$userId = $request->input('user_id');
		$note = $request->input('note');
		$check = User::where('id', $userId)->update(['note' => $note]);
		$arr = array('msg' => 'Something goes to wrong. Please try again lator', 'status' => false);
		if($check){
			$arr = array('msg' => 'Your note have saved successfully', 'status' => true, 'note'=>$note, 'user_id'=>$userId);
		}
		return Response()->json($arr);
	}
	
	public function update(UpdateRequest $request)
	{
		$this->handleInput($request);
		
		// Prevent user's role removal
		if (
			auth()->user()->id == request()->segment(3)
			|| Str::contains(URL::previous(), admin_uri('account'))
		) {
			$this->xPanel->disableSyncPivot();
		}
		
		return parent::updateCrud();
	}
	
	// PRIVATE METHODS
	
	/**
	 * @return array
	 */
	private function gender()
	{
		$entries = Gender::trans()->get();
		
		return $this->getTranslatedArray($entries);
	}
	
	/**
	 * @return array
	 */
	private function userType()
	{
		$entries = UserType::active()->get();
		
		$tab = [];
		if ($entries->count() > 0) {
			foreach ($entries as $entry) {
				$tab[$entry->id] = $entry->name;
			}
		}
		
		return $tab;
	}
	
	/**
	 * Handle Input values
	 *
	 * @param \App\Http\Requests\Admin\Request $request
	 */
	private function handleInput(Request $request)
	{
		$this->handlePasswordInput($request);

		// if ($this->isAdminUser($request)) {
		// 	request()->merge(['is_admin' => 1]);
		// } else {
		// 	request()->merge(['is_admin' => 0]);
		// }
	}
	
	/**
	 * Handle password input fields
	 *
	 * @param Request $request
	 */
	private function handlePasswordInput(Request $request)
	{
		// Remove fields not present on the user
		$request->request->remove('password_confirmation');
		
		/*
		// Encrypt password if specified
		if ($request->filled('password')) {
			$request->request->set('password', Hash::make($request->input('password')));
		} else {
			$request->request->remove('password');
		}
		*/
		
		// Encrypt password if specified (OK)
		if (request()->filled('password')) {
			request()->merge(['password' => Hash::make(request()->input('password'))]);
		} else {
			request()->replace(request()->except(['password']));
		}
	}
	
	/**
	 * Check if the set permissions are corresponding to the Staff permissions
	 *
	 * @param \App\Http\Requests\Admin\Request $request
	 * @return bool
	 */
	private function isAdminUser(Request $request)
	{
		$isAdmin = false;
		if (request()->filled('roles')) {
			$rolesIds = request()->input('roles');
			foreach ($rolesIds as $rolesId) {
				$role = Role::find($rolesId);
				if (!empty($role)) {
					$permissions = $role->permissions;
					if ($permissions->count() > 0) {
						foreach ($permissions as $permission) {
							if (in_array($permission->name, Permission::getStaffPermissions())) {
								$isAdmin = true;
							}
						}
					}
				}
			}
		}
		
		if (request()->filled('permissions')) {
			$permissionIds = request()->input('permissions');
			foreach ($permissionIds as $permissionId) {
				$permission = Permission::find($permissionId);
				if (in_array($permission->name, Permission::getStaffPermissions())) {
					$isAdmin = true;
				}
			}
		}
		
		return $isAdmin;
	}
}
