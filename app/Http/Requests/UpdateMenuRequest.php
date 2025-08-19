<?php

namespace Modules\Imenu\Http\Requests;

use Imagina\Icore\Http\Request\CoreFormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateMenuRequest extends CoreFormRequest
{
    public function rules(): array
    {
        return [
            'system_name' => 'required',
        ];
    }

    public function translationRules(): array
    {
        return [];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'system_name.required' => itrans('imenu::menus.validation.nameIsRequired'),
        ];
    }

    public function translationMessages(): array
    {
        return [];
    }

    public function getValidator(): Validator
    {
        return $this->getValidatorInstance();
    }
}
