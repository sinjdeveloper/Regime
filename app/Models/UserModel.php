<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['usename','password_hash','role'];

    public function checkAccess(string $username, string $password): ?array
    {
        $user = $this->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }

}
