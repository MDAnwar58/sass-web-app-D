<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationUser extends Pivot
{
    protected $table = 'organization_users';

    protected $fillable = ['organization_id', 'user_id', 'role', 'joined_at'];

    protected $casts = ['joined_at' => 'datetime'];
}
