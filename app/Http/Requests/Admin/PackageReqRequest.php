<?php


namespace App\Http\Requests\Admin;

class PackageReqRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'          => ['required', 'numeric'],
            'package_id'    => ['required', 'numeric'],
        ];
    }
}
