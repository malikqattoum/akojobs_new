<?php


namespace App\Http\Requests;

use App\Rules\BetweenRule;

class SendPostByEmailRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'sender_email'    => ['required', 'email', 'max:100'],
            'recipient_email' => ['required', 'email', 'max:100'],
            //'message' 	  => ['required', new BetweenRule(20, 500)],
            'post'            => ['required', 'numeric'],
        ];
        
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
