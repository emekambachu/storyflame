<?php

namespace App\Services\Admin;

use App\Models\Admin\Admin;

class AdminService
{
    public function admin(): Admin
    {
        return new Admin();
    }

    public function adminRelations(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->admin()->with('department');
    }

    public function adminById($id){
        return $this->adminRelations()->findOrFail($id);
    }
}
