<?php


namespace App\Http\Requests\Admin;

class PostPackageRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'          => ['required', 'min:2', 'max:255'],
            'description'    => ['required'],
            'price'         => ['required', 'numeric'],
            'post_num'         => ['required','numeric'],
            'period'         => ['required', 'numeric'],
        ];
    }
}
