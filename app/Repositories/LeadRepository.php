<?php

namespace App\Repositories;

use App\Models\Lead;

class LeadRepository
{
    public function getAll()
    {
        return Lead::all();
    }

    public function find($id)
    {
        return Lead::find($id);
    }

    public function create(array $data)
    {
        return Lead::create($data);
    }

    public function update(Lead $lead, array $data)
    {
        $lead->update($data);
        return $lead;
    }

    public function delete(Lead $lead)
    {
        return $lead->delete();
    }
}
