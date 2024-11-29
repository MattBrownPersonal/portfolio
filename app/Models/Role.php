<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];

    const VIVEDIA_ADMIN = 'superuser';

    const VENUE_ADMIN = 'siteadmin';

    const VENUE_STAFF = 'sitestaff';

    const VIVEDIA_ADMIN_ID = 1;

    const VIVEDIA_STAFF_ID = 2;

    const SITE_ADMIN_ID = 3;

    const SITE_STAFF_ID = 4;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
