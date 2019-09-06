<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpotUpdateRequest extends FormRequest
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
            'ParkingId' => 'required|integer',
            'IsOccupiedByDefault' => 'required|boolean'
        ];
    }
}
