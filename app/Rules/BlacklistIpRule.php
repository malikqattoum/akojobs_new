<?php


namespace App\Rules;

use App\Models\Blacklist;
use App\Helpers\Ip;
use Illuminate\Contracts\Validation\Rule;

class BlacklistIpRule implements Rule
{
	/**
	 * Determine if the validation rule passes.
	 * @todo: THIS RULE IS NOT USED IN THE APP.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		$ip = Ip::get();
		
		$blacklisted = Blacklist::ofType('ip')->where('entry', $ip)->first();
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
		return trans('validation.blacklist_ip_rule');
	}
}
