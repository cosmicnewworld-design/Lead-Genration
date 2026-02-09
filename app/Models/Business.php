<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'whatsapp_number', 'contact_email', 'target_audience'];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
