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
use App\Models\Resume;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Models\City;

class UserController extends PanelController
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
		$this->xPanel->setRoute(admin_uri('users'));
		$this->xPanel->setEntityNameStrings(trans('admin::messages.user'), trans('admin::messages.users'));
		if (!request()->input('order')) {
			$this->xPanel->orderBy('created_at', 'DESC');
		}

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		//$this->xPanel->addButtonFromModelFunction('line', 'impersonate', 'impersonateBtn', 'beginning');
		$this->xPanel->removeButton('delete');
		$this->xPanel->addButtonFromModelFunction('line', 'delete', 'deleteBtn', 'end');

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_verify_btn', 'bulkVerifyBtn', 'end');

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_complete_profile_verify_btn', 'bulkCompleteProfileBtn', 'end');
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_uploade_resume_btn', 'bulkUploadResumeBtn', 'end');

		$this->xPanel->addButtonFromModelFunction('top', 'bulk_send_custom_email_btn', 'bulkSendCustomEmailBtn', 'end');

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
		$this->xPanel->addFilter([
			'name'  => 'email',
			'type'  => 'text',
			'label' => 'Email',
		],
		false,
		function ($value) {
			$this->xPanel->addClause('where', 'email', 'LIKE', "%$value%");
		});

		$this->xPanel->addFilter([
			'name'  => 'phone',
			'type'  => 'text',
			'label' => 'Phone',
		],
			false,
			function ($value) {
				$this->xPanel->addClause('where', 'phone', 'LIKE', "%$value%");
		});
		$this->xPanel->addFilter([
			'name'  => 'note',
			'type'  => 'text',
			'label' => 'Note',
		],
			false,
			function ($value) {
				$this->xPanel->addClause('where', 'note', 'LIKE', "%$value%");
		});
		$this->xPanel->addFilter([
			'name'  => 'current_job_title',
			'type'  => 'text',
			'label' => 'Current job title',
		],
			false,
			function ($value) {
				$this->xPanel->addClause('where', 'current_job_title', 'LIKE', "%$value%");
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
		// 	});
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
				//$this->xPanel->addClause('orWhere', 'verified_phone', '=', 0);
			}
			if ($value == 2) {
				$this->xPanel->addClause('where', 'verified_email', '=', 1);
				//$this->xPanel->addClause('where', 'verified_phone', '=', 1);
			}
		});

		$this->xPanel->addFilter([
			'name'  => 'user_type_id',
			'type'  => 'dropdown',
			'label' => 'User Type',
		], [
			1 => 'Employer',
			2 => 'Jobseeker',
		], function ($value) {
				$this->xPanel->addClause('where', 'user_type_id', '=', $value);
				//$this->xPanel->addClause('orWhere', 'user_type_id', '=', $value);
		});
		
		$this->xPanel->addFilter([
		    'name'  => 'user_source',
		    'type'  => 'dropdown',
		    'label' => 'User Source',
		], [
		    1 => 'Registered',
		    2 => 'Referral',
		    3 => 'Easy Apply',
		    4 => 'Invited',
		], function ($value) {
		    $this->xPanel->addClause('where', 'user_source', '=', $value);
		    //$this->xPanel->addClause('orWhere', 'user_type_id', '=', $value);
		});

		$this->xPanel->addFilter([
			'name'  => 'resume',
			'type'  => 'dropdown',
			'label' => 'Resume',
		], [
			1 => 'Have resume',
			2 => 'Don\'t have resume',
		], function ($value) {
			if($value == 1)
			{
				$this->xPanel->query = $this->xPanel->query->whereHas('resumes');
			}
			else
			{
				$this->xPanel->query = $this->xPanel->query->whereDoesntHave('resumes');
			}
		});

		$roles = include(base_path() . '/resources/lang/en/roles.php'); 
		asort($roles);
		$rolesKeys = array_keys($roles);
		$jobRoleConfig = [];
		foreach ($rolesKeys as $value) {
			$jobRoleConfig[$value] = t($value,[],'roles');
		}

		$this->xPanel->addFilter([
			'name'  => 'job_role',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Current Job Role'),
		],
		$jobRoleConfig
		,function ($value) {
			$this->xPanel->addClause('where', 'job_role', '=', $value);
			//$this->xPanel->addClause('orWhere', 'job_role', '=', $value);
		});

		$this->xPanel->addFilter([
			'name'  => 'sec_job_role',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Secondary Job Role'),
		],
		$jobRoleConfig
		,function ($value) {
			$this->xPanel->addClause('where', 'sec_job_role', '=', $value);
			//$this->xPanel->addClause('orWhere', 'sec_job_role', '=', $value);
		});

		$industry = include(base_path() . '/resources/lang/en/industry.php');
		asort($industry); 
		$industryKeys = array_keys($industry);
		$industryConfig = [];
		foreach ($industryKeys as $value) {
			$industryConfig[$value] = t($value,[],'industry');
		}

		$residenceCountry = include(base_path() . '/resources/lang/en/residenceCountry.php');
		asort($residenceCountry); 
		$residenceCountryKeys = array_keys($residenceCountry);
		$residenceCountryConfig = [];
		foreach ($residenceCountryKeys as $value) {
			$residenceCountryConfig[$value] = t($value,[],'residenceCountry');
		}


		$this->xPanel->addFilter([
			'name'  => 'industry',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Industry'),
		],
		$industryConfig
		,function ($value) {
			$this->xPanel->addClause('where', 'industry', '=', $value);
			//$this->xPanel->addClause('orWhere', 'industry', '=', $value);
		});

		$this->xPanel->addFilter([
			'name'  => 'residence_country',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.Residence Country'),
		],
		$residenceCountryConfig
		,function ($value) {
			$this->xPanel->addClause('where', 'residence_country', '=', $value);
			//$this->xPanel->addClause('orWhere', 'residence_country', '=', $value);
		});


		$experience = include(base_path() . '/resources/lang/en/experience.php'); 
		$experienceKeys = array_keys($experience);
		$experienceConfig = [];
		foreach ($experienceKeys as $value) {
			$experienceConfig[$value] = t($value,[],'experience');
		}

		$this->xPanel->addFilter([
			'name'  => 'user_experience',
			'type'  => 'dropdown',
			'label' => trans('admin::messages.User Experience'),
		],
		$experienceConfig
		,function ($value) {
			$this->xPanel->addClause('where', 'user_experience', '=', $value);
			//$this->xPanel->addClause('orWhere', 'user_experience', '=', $value);
		});

		$this->xPanel->addFilter([
			'name'  => 'experience',
			'type'  => 'dropdown',
			'label' => 'Experience section',
		], [
			1 => 'Filled',
			2 => 'Not filled',
		], function ($value) {
			if($value == 1)
			{
				$this->xPanel->query = $this->xPanel->query->whereHas('experiences');
			}
			else
			{
				$this->xPanel->query = $this->xPanel->query->whereDoesntHave('experiences');
			}
		});

		$this->xPanel->addFilter([
			'name'  => 'education',
			'type'  => 'dropdown',
			'label' => 'Education section',
		], [
			1 => 'Filled',
			2 => 'Not filled',
		], function ($value) {
			if($value == 1)
			{
				$this->xPanel->query = $this->xPanel->query->whereHas('userEducations');
			}
			else
			{
				$this->xPanel->query = $this->xPanel->query->whereDoesntHave('userEducations');
			}
		});

		$this->xPanel->addFilter([
			'name'  => 'language',
			'type'  => 'dropdown',
			'label' => 'Language section',
		], [
			1 => 'Filled',
			2 => 'Not filled',
		], function ($value) {
			if($value == 1)
			{
				$this->xPanel->query = $this->xPanel->query->whereHas('userLanguages');
			}
			else
			{
				$this->xPanel->query = $this->xPanel->query->whereDoesntHave('userLanguages');
			}
		});

		$this->xPanel->addFilter([
			'name'  => 'reference',
			'type'  => 'dropdown',
			'label' => 'Reference section',
		], [
			1 => 'Filled',
			2 => 'Not filled',
		], function ($value) {
			if($value == 1)
			{
				$this->xPanel->query = $this->xPanel->query->whereHas('userReferences');
			}
			else
			{
				$this->xPanel->query = $this->xPanel->query->whereDoesntHave('userReferences');
			}
		});

		$this->xPanel->addFilter([
			'name'  => 'skill',
			'type'  => 'dropdown',
			'label' => 'Skill section',
		], [
			1 => 'Filled',
			2 => 'Not filled',
		], function ($value) {
			if($value == 1)
			{
				$this->xPanel->query = $this->xPanel->query->whereHas('userSkills');
			}
			else
			{
				$this->xPanel->query = $this->xPanel->query->whereDoesntHave('userSkills');
			}
		});

		$this->xPanel->addFilter([
			'name'  => 'training',
			'type'  => 'dropdown',
			'label' => 'Trainings section',
		], [
			1 => 'Filled',
			2 => 'Not filled',
		], function ($value) {
			if($value == 1)
			{
				$this->xPanel->query = $this->xPanel->query->whereHas('userTrainings');
			}
			else
			{
				$this->xPanel->query = $this->xPanel->query->whereDoesntHave('userTrainings');
			}
		});

		$cities = City::where([['active', '=', 1], ['country_code', '=', 'IQ']])->get();
		$citiesConfig = [];
		foreach ($cities as $value) {
			$citiesConfig[$value->id] = $value->name;
		}

		$this->xPanel->addFilter([
			'name'  => 'city_id',
			'type'  => 'dropdown',
			'label' => t('Where?'),
		],
		$citiesConfig
		,function ($value) {
			$this->xPanel->addClause('where', 'city_id', '=', $value);
			//$this->xPanel->addClause('orWhere', 'job_role', '=', $value);
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
				'label' => trans("admin::messages.Name"),
			]);
			$this->xPanel->addColumn([
				'name'  => 'email',
				'label' => trans("admin::messages.Email"),
			]);
			$this->xPanel->addColumn([
				'name'      => 'user_type_id',
				'label'     => trans("admin::messages.Type"),
				'model'     => 'App\Models\UserType',
				'entity'    => 'userType',
				'attribute' => 'name',
				'type'      => 'select',
			]);

			$this->xPanel->addColumn([
				'label'         => trans("admin::messages.Resume"),
				'name'          => 'filename',
				'type'          => 'model_function',
				'function_name' => 'getResumeHtml',
			]);

			$this->xPanel->addColumn([
				'label'         => 'Profile',
				'name'          => 'profile',
				'type'          => 'model_function',
				'function_name' => 'getUserProfileLink',
			]);
			$this->xPanel->addColumn([
				'label'         => trans("admin::messages.Note"),
				'name'          => 'note',
				'type'          => 'model_function',
				'function_name' => 'getNote',
			]);
			$this->xPanel->addColumn([
			    'label'         => "Source",
			    'name'          => 'user_source',
			    'type'          => 'model_function',
			    'function_name' => 'getUserSource',
			]);
			$this->xPanel->addColumn([
				'label'         => trans("admin::messages.Current Job Role"),
				'name'          => 'job_role',
				'type'          => 'model_function',
				'function_name' => 'getJobRole',
			]);

			$this->xPanel->addColumn([
				'label'         => trans("admin::messages.Secondary Job Role"),
				'name'          => 'sec_job_role',
				'type'          => 'model_function',
				'function_name' => 'getSecondaryJobRole',
			]);

			$this->xPanel->addColumn([
				'label'         => trans("admin::messages.Industry"),
				'name'          => 'industry',
				'type'          => 'model_function',
				'function_name' => 'getIndustry',
			]);

			$this->xPanel->addColumn([
				'label'         => trans("admin::messages.User Experience"),
				'name'          => 'user_experience',
				'type'          => 'model_function',
				'function_name' => 'getUserExperience',
			]);

			$this->xPanel->addColumn([
				'label'         => trans("admin::messages.Residence Country"),
				'name'          => 'residence_country',
				'type'          => 'model_function',
				'function_name' => 'getResidenceCountry',
			]);

			$this->xPanel->addColumn([
				'label'         => "Current Job Title",
				'name'          => 'current_job_title',
				'type'          => 'model_function',
				'function_name' => 'getCurrentJobTitle',
			]);

			$this->xPanel->addColumn([
				'label'         => trans("admin::messages.Phone"),
				'name'          => 'phone',
				'type'          => 'model_function',
				'function_name' => 'getPhoneNumber',
			]);
			$this->xPanel->addColumn([
				'label'         => "Profile status",
				'name'          => 'profile_status',
				'type'          => 'model_function',
				'function_name' => 'getProfileStatus',
			]);
			// $this->xPanel->addColumn([
			// 	'label'         => trans("admin::messages.Country"),
			// 	'name'          => 'country_code',
			// 	'type'          => 'model_function',
			// 	'function_name' => 'getCountryHtml',
			// ]);
			$this->xPanel->addColumn([
				'name'          => 'verified_email',
				'label'         => trans("admin::messages.Verified Email"),
				'type'          => 'model_function',
				'function_name' => 'getVerifiedEmailHtml',
			]);
			// $this->xPanel->addColumn([
			// 	'name'          => 'verified_phone',
			// 	'label'         => trans("admin::messages.Verified Phone"),
			// 	'type'          => 'model_function',
			// 	'function_name' => 'getVerifiedPhoneHtml',
			// ]);

			// $this->xPanel->addColumn([ // note duplicate just for reading clearly
			// 	'label'         => trans("admin::messages.Full Note"),
			// 	'name'          => 'full_note',
			// 	'type'          => 'model_function',
			// 	'function_name' => 'getFullNote',
			// ]);
			
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
				'name'              => 'name_ar',
				'label'             => 'Arabic Name',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'Arabic Name',
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
				],
			]);
			// $this->xPanel->addField([
			// 	'label'             => trans("admin::messages.Country"),
			// 	'name'              => 'country_code',
			// 	'model'             => 'App\Models\Country',
			// 	'entity'            => 'country',
			// 	'attribute'         => 'asciiname',
			// 	'type'              => 'select2',
			// 	'wrapperAttributes' => [
			// 		'class' => 'form-group col-md-6',
			// 	],
			// ]);

			$this->xPanel->addField([
				'label'             => trans("admin::messages.Residence Country"),
				'name'              => 'residence_country',
				'type'              => 'select2_from_array',
				'options'           => $residenceCountryConfig,
				'allows_null'       => false,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'user_type_id',
				'label'             => trans("admin::messages.Type"),
				'type'              => 'select2_from_array',
				'options'           => $this->userType(),
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
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
				'name'              => 'job_role',
				'label'             => 'Job Role',
				'type'              => 'select2_from_array',
				'options'           => trans("admin::messages.job_roles"),
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);

			$this->xPanel->addField([
				'name'              => 'sec_job_role',
				'label'             => 'Scondary Job Role',
				'type'              => 'select2_from_array',
				'options'           => trans("admin::messages.job_roles"),
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'industry',
				'label'             => 'Industry',
				'type'              => 'select2_from_array',
				'options'           => trans("admin::messages.industries_list"),
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'user_experience',
				'label'             => 'User Experience',
				'type'              => 'select2_from_array',
				'options'           => trans("admin::messages.experience_list"),
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'current_job_title',
				'label'             => 'Current Job Title',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'Current Job Title',
				],
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'label'             => 'Nationality',
				'name'              => 'nationality',
				'type'              => 'select2_from_array',
				'options'           => $residenceCountryConfig,
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'martial_status',
				'label'             => 'Martial Status',
				'type'              => 'select2_from_array',
				'options'           => trans("admin::messages.martial_status"),
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'num_dependents',
				'label'             => 'Number of dependents',
				'type'              => 'select2_from_array',
				'options'           => range(0,20),
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'preferred_job_title',
				'label'             => 'Preferred job title',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'Preferred job title',
				],
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'trgt_job_location',
				'label'             => 'Target job location',
				'type'              => 'select2_from_array',
				'options'           => $residenceCountryConfig,
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'trgt_city',
				'label'             => 'Target city',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'Target city',
				],
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'total_exp',
				'label'             => 'Total Experience',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'Ex: 5 years, 2 months',
				],
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'city_id',
				'label'             => 'City',
				'type'              => 'select2_from_array',
				'options'           => $citiesConfig,
				'allows_null'       => true,
				'wrapperAttributes' => [
					'class' => 'form-group col-md-6',
				],
			]);
			$this->xPanel->addField([
				'name'              => 'pref_lang',
				'label'             => 'Preferred language',
				'type'              => 'select2_from_array',
				'options'           => ["ar"=>"العربية","en"=>"English"],
				'allows_null'       => true,
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
			if (auth()->user()->id != request()->segment(3)) {
				$this->xPanel->addField([
					'name'  => 'separator',
					'type'  => 'custom_html',
					'value' => '<hr>'
				]);
				$this->xPanel->addField([
					// two interconnected entities
					'label'             => trans('admin::messages.user_role_permission'),
					'field_unique_name' => 'user_role_permission',
					'type'              => 'checklist_dependency',
					'name'              => 'roles_and_permissions', // the methods that defines the relationship in your Model
					'subfields'         => [
						'primary'   => [
							'label'            => trans('admin::messages.roles'),
							'name'             => 'roles', // the method that defines the relationship in your Model
							'entity'           => 'roles', // the method that defines the relationship in your Model
							'entity_secondary' => 'permissions', // the method that defines the relationship in your Model
							'attribute'        => 'name', // foreign key attribute that is shown to user
							'model'            => config('permission.models.role'), // foreign key model
							'pivot'            => true, // on create&update, do you need to add/delete pivot table entries?]
							'number_columns'   => 3, //can be 1,2,3,4,6
						],
						'secondary' => [
							'label'          => mb_ucfirst(trans('admin::messages.permission_singular')),
							'name'           => 'permissions', // the method that defines the relationship in your Model
							'entity'         => 'permissions', // the method that defines the relationship in your Model
							'entity_primary' => 'roles', // the method that defines the relationship in your Model
							'attribute'      => 'name', // foreign key attribute that is shown to user
							'model'          => config('permission.models.permission'), // foreign key model
							'pivot'          => true, // on create&update, do you need to add/delete pivot table entries?]
							'number_columns' => 3, //can be 1,2,3,4,6
						],
					],
				]);
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

		// $this->xPanel->addField([
		// 	'label'             => trans("admin::messages.Country"),
		// 	'name'              => 'country_code',
		// 	'model'             => 'App\Models\Country',
		// 	'entity'            => 'country',
		// 	'attribute'         => 'asciiname',
		// 	'type'              => 'select2',
		// 	'wrapperAttributes' => [
		// 		'class' => 'form-group col-md-6',
		// 	],
		// ]);

		$residenceCountry = include(base_path() . '/resources/lang/en/residenceCountry.php');
		asort($residenceCountry); 
		$residenceCountryKeys = array_keys($residenceCountry);
		$residenceCountryConfig = [];
		foreach ($residenceCountryKeys as $value) {
			$residenceCountryConfig[$value] = t($value,[],'residenceCountry');
		}

		$this->xPanel->addField([
			'label'             => trans("admin::messages.Residence Country"),
			'name'              => 'residence_country',
			'type'              => 'select2_from_array',
			'options'           => $residenceCountryConfig,
			'allows_null'       => true,
			'wrapperAttributes' => [
				'class' => 'form-group col-md-6',
			],
		]);

		$this->xPanel->addField([
			'name'              => 'user_type_id',
			'label'             => trans("admin::messages.Type"),
			'type'              => 'select2_from_array',
			'options'           => $this->userType(),
			'allows_null'       => true,
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
