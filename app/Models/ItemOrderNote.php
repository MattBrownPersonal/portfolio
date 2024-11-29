<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOrderNote extends Model
{
    use HasFactory;

    public function oldStatus()
    {
        return $this->belongsTo(Status::class, 'old_status_id');
    }

    public function newStatus()
    {
        return $this->belongsTo(Status::class, 'new_status_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
