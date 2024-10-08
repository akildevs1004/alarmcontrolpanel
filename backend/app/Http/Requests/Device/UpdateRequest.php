<?php

namespace App\Http\Requests\Device;

use App\Traits\failedValidationWithName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    use failedValidationWithName;
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'min:2', 'max:50'],
            //'short_name' => ['required', 'nullable', 'min:3', 'max:4'],
            // 'device_id' => ['required', 'min:3', 'max:100'],
            'location' => ['required', 'min:2', 'max:300'],
            'company_id' => ['required', 'min:1', 'integer'],
            // 'status_id' => ['required', 'min:1', 'integer'],

            'model_number' => ['required', 'min:6', 'max:50'],
            'device_type' =>  'required',
            'serial_number' => ['required'],
            'mode' => ['nullable'],

            'ip' => 'required|ip',
            'port' => 'required',
            'function' => 'nullable',
            'utc_time_zone' => 'required',
            'branch_id' => 'nullable',
            'serial_number' => 'required',
            'alarm_delay_minutes' => 'required',
            'customer_id' => 'nullable',
            'threshold_temperature' => ['nullable'],

        ];
    }
}
