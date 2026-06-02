<?php

namespace App\Service;

use App\Models\Contact;

class ContactService
{
    public function submitInquiry(array $data)
    {
        if (isset($data['unit_id']) && !empty($data['unit_id'])) {
            $unit = \App\Models\Unit::find($data['unit_id']);
            if ($unit && $unit->owner_id) {
                $data['seller_id'] = $unit->owner_id;
            }
        }
        return Contact::create($data);
    }

    public function getSellerMessages($user)
    {
        return Contact::with('unit')->where('seller_id', $user->id)->latest()->paginate(10);
    }
}
