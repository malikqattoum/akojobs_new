<?php


namespace App\Http\Requests;

class ResetPasswordRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'login'    => ['required'],
            'password' => ['required', 'min:8', 'max:60', 'dumbpwd', 'confirmed'],
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
