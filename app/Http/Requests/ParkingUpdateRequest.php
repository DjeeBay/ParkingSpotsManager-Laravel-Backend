<?php

namespace App\Http\Requests;

class ParkingUpdateRequest extends ApiRequest
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
            'Name' => 'required|string|min:1',
            'Address' => 'string|nullable',
            'Latitude' => 'numeric|regex:/^\d+(\.\d{1,8})?$/|nullable',
            'Longitude' => 'numeric|regex:/^\d+(\.\d{1,8})?$/|nullable',
        ];
    }
}
