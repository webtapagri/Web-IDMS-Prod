<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoadCategoryRequest extends FormRequest
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
            'category_name' => [
				'required', 
				Rule::unique('TM_ROAD_CATEGORY')->where(function ($query) {
					return $query->where('status_id',$this->get('status_id'))
								 ->whereRaw('deleted_at is null');
				})
			],
            'category_code' => [
				'required', 
				Rule::unique('TM_ROAD_CATEGORY')->where(function ($query) {
					return $query->where('status_id',$this->get('status_id'))
								 ->whereRaw('deleted_at is null');
				}),
				'numeric'
			],
			'category_initial' => [
				'required', 
				Rule::unique('TM_ROAD_CATEGORY')->where(function ($query) {
					return $query->whereRaw('deleted_at is null');
				})
			],
            'status_id' => 'required',
        ];
    }
	
	public function messages()
	{
		// return [
			// 'category_name.required' => 'Category Name harus diisi',
			// 'category_initial.required'  => 'Category Initial harus diisi',
			// 'category_code.required'  => 'Category Code harus diisi',
			// 'category_code.numeric'  => 'Category Code harus berupa angka',
			// 'status_id.required'  => 'Status harus diisi',
		// ];
		
		return [
			'required'  => 'Harap bagian :attribute di isi.',
			'unique'    => ':attribute sudah digunakan',
		];
	}
	
}
