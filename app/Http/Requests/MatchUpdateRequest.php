<?php
declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatchUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'home_goals' => 'required|integer',
            'away_goals' => 'required|integer',
        ];
    }

    public function all($keys = null): array
    {
        $data = parent::all($keys);
        $data['home_goals'] = (int) $this->input('home_goals');
        $data['away_goals'] = (int) $this->input('away_goals');

        return $data;
    }
}
