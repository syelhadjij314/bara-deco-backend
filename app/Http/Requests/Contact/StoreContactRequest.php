<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'phone'   => ['required', 'string', 'max:20'],
            'email'   => ['required', 'email', 'max:255'],
            'service' => ['required', 'in:peinture-interieur,peinture-exterieur,faux-plafond,decoration,autre'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Votre nom est obligatoire.',
            'phone.required'   => 'Votre téléphone est obligatoire.',
            'email.required'   => 'Votre email est obligatoire.',
            'service.required' => 'Veuillez sélectionner un service.',
            'message.required' => 'Votre message est obligatoire.',
            'message.min'      => 'Le message doit contenir au moins 10 caractères.',
        ];
    }
}
