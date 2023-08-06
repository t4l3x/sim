<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeekRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'week' => 'nullable|sometimes|integer',
        ];
    }

    public function all($keys = null): array
    {
        $data = parent::all($keys);
        $data['week'] = $this->route('week');


        return $data;
    }
}
