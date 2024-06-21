<?php

namespace App\Services\Admin;

use App\Models\Admin\Admin;

class AdminService
{
    public function admin(): Admin
    {
        return new Admin();
    }
}
