<?php

namespace Featherwebs\Mari\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => 'required|min:3',
            'slug'          => 'required|unique:pages,slug,' . $this->page->id,
            'sub_title'     => '',
            'view'          => '',
            'content'       => '',
            'page_id'       => 'exists:pages,id|nullable',
            'images.*.file' => 'image'
        ];
    }

    public function data()
    {
        return [
            'title'            => $this->get('title'),
            'sub_title'        => $this->get('sub_title'),
            'slug'             => str_slug($this->get('slug')),
            'view'             => $this->get('view'),
            'content'          => $this->get('content'),
            'custom'           => $this->input('custom', []),
            'page_id'          => $this->get('page_id'),
            'meta_title'       => $this->get('meta_title'),
            'meta_description' => $this->get('meta_description'),
            'meta_keywords'    => $this->get('meta_keywords'),
            'is_published'     => $this->get('is_published', 'false') == 'true',
        ];
    }
}
