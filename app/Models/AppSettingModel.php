<?php

namespace App\Models;

use CodeIgniter\Model;

class AppSettingModel extends Model
{
    protected $table = 'app_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    protected $protectFields = true;
    protected $allowedFields = ['libelle', 'value'];

    protected $useTimestamps = false;
}
