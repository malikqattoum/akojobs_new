<?php


namespace App\Http\Requests;

use App\Rules\BetweenRule;

class ReportRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'report_type_id' => ['required', 'not_in:0'],
			'email'          => ['required', 'email', 'max:100'],
			'message'        => ['required', new BetweenRule(20, 1000)],
			'post_id'        => ['required', 'numeric'],
		];
		
		// reCAPTCHA
		$rules = $this->recaptchaRules($rules);
		
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
