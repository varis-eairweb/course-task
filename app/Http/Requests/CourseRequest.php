<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required',
            'module' => 'required|array',
            'module.*.title' => 'required|string|max:255',
            'module.*.status' => 'required',
            'module.*.is_testable' => 'required',
            'module.*.materials' => 'array',
            'module.*.test' => 'array|required_if:is_testable,1',
            'module.*.test.title' => 'required_if:is_testable,1',
            'module.*.test.duration' => 'required_if:is_testable,1',
            'module.*.test.instructions' => 'required_if:is_testable,1',
            'module.*.test.questions.*' => 'required_if:is_testable,1',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'The course title is required.',
            'title.string' => 'The course title must be a string.',
            'title.max' => 'The course title may not be greater than 255 characters.',

            'description.required' => 'The course description is required.',

            'status.required' => 'The course status is required.',

            'module.required' => 'At least one module is required.',
            'module.array' => 'Modules must be an array.',

            'module.*.title.required' => 'Each module title is required.',
            'module.*.title.string' => 'Each module title must be a string.',
            'module.*.title.max' => 'Each module title may not be greater than 255 characters.',

            'module.*.status.required' => 'Each module status is required.',

            'module.*.is_testable.required' => 'The is_testable field is required for each module.',

            'module.*.materials.array' => 'Materials must be an array.',

            'module.*.test.array' => 'Test must be an array.',
            'module.*.test.required_if' => 'A test is required if the module is tastable.',

            'module.*.test.title.required' => 'The test title is required.',

            'module.*.test.duration.required' => 'The test duration is required.',

            'module.*.test.instructions.required' => 'The test instructions are required.',

            'module.*.test.questions.*.required' => 'Each test question is required.',
        ];
    }
}
