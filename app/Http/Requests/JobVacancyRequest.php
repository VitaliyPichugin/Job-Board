<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobVacancyRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sort_date' => 'nullable|in:asc,desc',
            'sort_response' => 'nullable|in:asc,desc',
            'tags' => 'nullable|array',
            'tags.*' => 'integer',
            'date' => 'nullable|date',
            'week' => 'nullable|integer|min:1:max:52',
            'day' => 'nullable|integer|min:1|max:365',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer|min:1970|max:2072',
        ];
    }
}
