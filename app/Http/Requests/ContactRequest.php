<?php


namespace App\Http\Requests;

use App\Rules\BetweenRule;
use App\Rules\BlacklistDomainRule;
use App\Rules\BlacklistEmailRule;

class ContactRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'first_name' => ['required', new BetweenRule(2, 100)],
			'last_name'  => ['required', new BetweenRule(2, 100)],
			'email'      => ['required', 'email', new BlacklistEmailRule(), new BlacklistDomainRule()],
			'message'    => ['required', new BetweenRule(5, 500)],
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
