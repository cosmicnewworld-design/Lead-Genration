<?php

namespace App\Repositories;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;

class BusinessRepository
{
    public function getAll()
    {
        return Auth::user()->tenant->businesses()->latest()->paginate(5);
    }

    public function create(array $data)
    {
        return Business::create($data);
    }

    public function findWithLeads($id)
    {
        $business = Auth::user()->tenant->businesses()->findOrFail($id);
        $business->load(['leads' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);
        return $business;
    }

    public function update(Business $business, array $data)
    {
        $business->update($data);
        return $business;
    }

    public function delete(Business $business)
    {
        return $business->delete();
    }
}
