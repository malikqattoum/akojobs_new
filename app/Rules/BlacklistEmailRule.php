<?php


namespace App\Rules;

use App\Models\Blacklist;
use Illuminate\Contracts\Validation\Rule;

class BlacklistEmailRule implements Rule
{
	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		$value = strtolower($value);
		
		$blacklisted = Blacklist::ofType('email')->where('entry', $value)->first();
		if (!empty($blacklisted)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return trans('validation.blacklist_email_rule');
	}
}
