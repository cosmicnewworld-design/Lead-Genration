<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'original_filename',
        'storage_path',
        'file_size',
    ];

    /**
     * Get the lead that owns the attachment.
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the user who uploaded the attachment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }\n
    /**
     * Get the formatted file size.
     *
     * @return string
     */
    public function getFormattedFileSizeAttribute()
    {
        $size = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        if ($size == 0) {
            return '0 B';
        }

        $i = floor(log($size, 1024));
        return @round($size / pow(1024, $i), 2) . ' ' . $units[$i];
    }
}
