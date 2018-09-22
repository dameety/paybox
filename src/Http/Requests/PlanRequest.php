<?php

namespace Dameety\Paybox\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
        if ($this->is( config('paybox.uri') . '/plan/store') ) {
            return [
                'name' => 'required|unique:plans',
                'identifier' => 'required|unique:plans',
                'amount' => 'required|numeric',
                'interval' => 'required|max:8',
            ];
        } elseif ($this->is('ajax/user-subscription/store')) {
            return [
                'planName' => 'required|string',
                'stripeToken' => 'required|string'
            ];
        }
    }
}
