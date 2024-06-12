<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer',
            'image' => 'required|image:png,jpg,webp',
        ];

        if($this->getMethod() === 'POST') {
            return $rules;
        }
        if($this->getMethod() === 'PUT') {
            return [
                'id' => 'required|integer|exists:products,id'
            ] + $rules;
        }
        if($this->getMethod() === 'PATCH') {
            return [
                'id' => 'required|integer|exists:products,id'
            ];
        }
        if($this->getMethod() === 'DELETE') {
            return [
                'id' => 'required|integer|exists:products,id'
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
        $data['id'] = $this->route('product');
      }

      return $data;
    }

    public function messages() {
        return [
            'name.required' => 'Имя продукта обязательно',
            'description.required' => 'Описание продукта обязательно',
            'price.integer' => 'Цена должна быть числом'
        ];
    }
}
