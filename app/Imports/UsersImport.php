<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        return new User([
            'id'         => $row[0],
            'name'       => $row[1],
            'email'      => $row[2],
            'is_active'  => $row[3],
            'is_blocked' => $row[4],
            'created_at' => $row[5],
        ]);
    }
}
