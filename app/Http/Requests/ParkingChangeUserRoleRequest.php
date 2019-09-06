<?php

namespace App\Http\Requests;

class ParkingChangeUserRoleRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'Id' => 'required|integer',
            'UserId' => 'required|integer',
            'ParkingId' => 'required|integer',
            'IsAdmin' => 'required|boolean'
        ];
    }
}
