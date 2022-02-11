<?php


namespace App\Http\Requests;

use App\Rules\BetweenRule;
use App\Rules\BlacklistTitleRule;
use App\Rules\BlacklistWordRule;
use App\Rules\BlacklistDomainRule;
use App\Rules\BlacklistEmailRule;

class CompanyRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Validation Rules
		$rules = [
			'company.name'        => ['required', new BetweenRule(2, 200), new BlacklistTitleRule()],
            'company.description' => ['required', new BetweenRule(5, 1000), new BlacklistWordRule()],
        ];

        if(strpos($_SERVER['HTTP_REFERER'], 'companies/create') === false)
        {
            $rules['company.email'] = ['required','max:100', new BlacklistEmailRule(), new BlacklistDomainRule()];
            $rules['company.company_type'] = ['required', new BetweenRule(1, 2)];
            $rules['company.company_size'] = ['required', new BetweenRule(1, 2)];
            $rules['company.company_location'] = ['required', new BetweenRule(1, 2)];
            $rules['current_job_title'] = ['required', new BetweenRule(2, 200), new BlacklistTitleRule()];
        }
	
		// Check 'logo' is required
		if ($this->hasFile('company.logo')) {
			$rules['company.logo'] = [
				'required',
				'image',
				'mimes:' . getUploadFileTypes('image'),
				'max:' . (int)config('settings.upload.max_file_size', 1000)
			];
		}
        
        return $rules;
    }
    
    /**
     * @return array
     */
    public function messages()
    {
        $messages = [];
        
        return $messages;
    }
}
