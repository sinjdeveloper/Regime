<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password_hash', 'role'];

    public function checkAccess(string $username, string $password): ?array
    {
        $user = $this->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }
    public function checkViaEmail(string $email, string $password)
    {
        $user = $this->select('user.*, client.id AS client_id, client.email, client.genre, client.estGold, client.argent')
            ->join('client', 'client.id_user = user.id')
            ->where('client.email', $email)
            ->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }

    public function checkViaUsername(string $username, string $password)
    {
        $user = $this->select('user.*, client.id AS client_id, client.email, client.genre, client.estGold, client.argent')
            ->join('client', 'client.id_user = user.id', 'left')
            ->where('user.username', $username)
            ->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }
}
