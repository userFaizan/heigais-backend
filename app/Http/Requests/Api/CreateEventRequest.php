<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
            "category_id"=>'required',
            "type_id"=>'required',
            "title"=>'required|string',
            // "sub_title"=>'string',
            "description"=>'required|string',
            "date_from"=>'required|date',
            "date_to"=>'required|date',
            "address"=>'required|string',
            "lat"=>'required',
            "lng"=>'required',
        ];
    }
}
