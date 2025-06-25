<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (!isset($row['reg_number'])) {
            throw new \Exception("Missing 'reg_number' in Excel file.");
        }

        $data = [
            'name' => $row['name'],
            'reg_number' => $row['reg_number'],
            'email' => $row['email'],
            'role' => $row['role'],
            'password' => Hash::make($row['password']),
        ];

        return $row['role'] === 'admin'
            ? Admin::firstOrCreate(['reg_number' => $row['reg_number']], $data)
            : User::firstOrCreate(['reg_number' => $row['reg_number']], $data);
    }
}
