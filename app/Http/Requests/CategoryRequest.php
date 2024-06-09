<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string',
        ];

        if($this->getMethod() === 'POST') {
            return $rules;
        }
        if($this->getMethod() === 'PUT') {
            return [
                    'id' => 'required|integer|exists:categories,id'
                ] + $rules;
        }
        if($this->getMethod() === 'PATCH') {
            return [
                'id' => 'required|integer|exists:categories,id'
            ];
        }
        if($this->getMethod() === 'DELETE') {
            return [
                'id' => 'required|integer|exists:categories,id'
            ];
        }

        return $rules;
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        if(
            $this->getMethod() === 'DELETE'
            || $this->getMethod() === 'PUT'
            || $this->getMethod() === 'PATCH'
        ) {
            $data['id'] = $this->route('category');
        }

        return $data;
    }

    public function messages() {
        return [
            'name.required' => 'Имя категории обязательно'
        ];
    }
}
