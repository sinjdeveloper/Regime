<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'client';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['id_user', 'email', 'genre', 'dateNaissance', 'poids', 'taille', 'estGold', 'argent'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];


    // Validation
    protected $validationRules = [
        'id_user' => 'required|integer|greater_than[0]',
        'email' => 'required|valid_email|is_unique[client.email]',
        'genre' => 'required|in_list[homme,femme,autre]',
        'dateNaissance' => 'required|valid_date[Y-m-d]',
        'poids' => 'required|numeric|greater_than[0]',
        'taille' => 'required|numeric|greater_than[0]',
        'estGold' => 'permit_empty|in_list[0,1]',
        'argent' => 'numeric|greater_than_equal_to[0]',
    ];
    protected $validationMessages = [
        'email' => ['is_unique' => 'Cet email est déjà utilisé'],
    ];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Récupère tous les objectifs du client
     *
     * @param int $clientId ID du client
     * @return array Array d'objectifs avec détails
     */
    public function getGoals(int $clientId): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('goalpoids');
        $builder->select('goalpoids.*,objectif.libelle as objectif_libelle');
        $builder->join('objectif', 'objectif.id = goalpoids.objectif_id', 'left');
        $builder->where('goalpoids.client_id', $clientId);
        return $builder->get()->getResultArray();
    }

    /**
     * Applique la réduction Gold (dynamique) au prix si applicable
     *
     * @param float $prix Prix original
     * @return float Prix avec réduction si Gold, sinon prix original
     */
    public function applyGoldDiscount(float $prix): float
    {
        if ($this->estGold) {
            $discountPercent = \App\Services\AppSettingsService::getGoldDiscount();
            $multiplier = 1 - ($discountPercent / 100);
            return round($prix * $multiplier, 2);
        }
        return round($prix, 2);
    }

    /**
     * Mise à jour du solde (wallet)
     *
     * @param int   $clientId ID du client
     * @param float $montant  Montant à ajouter (négatif pour débiter)
     * @return bool Succès de la mise à jour
     */
    public function updateBalance(int $clientId, float $montant): bool
    {
        $client = $this->find($clientId);
        if (!$client) {
            return false;
        }

        $nouveauSolde = $client['argent'] + $montant;
        if ($nouveauSolde < 0) {
            return false;
        }

        return $this->update($clientId, ['argent' => $nouveauSolde]);
    }

    public static function validateUserInfo($data)
    {
        $errors = [];
        $fieldErrors = [];

        if (empty($data['prenom'])) {
            $errors[] = "Prénom requis";
            $fieldErrors['prenom'] = true;
        }
        if (empty($data['nom'])) {
            $errors[] = "Nom requis";
            $fieldErrors['nom'] = true;
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
            $fieldErrors['email'] = true;
        }
        if (empty($data['genre'])) {
            $errors[] = "Genre requis";
            $fieldErrors['genre'] = true;
        }
        if (empty($data['password'])) {
            $errors[] = "Mot de passe requis";
            $fieldErrors['password'] = true;
        }
        if (($data['password'] ?? null) !== ($data['password_confirm'] ?? null)) {
            $errors[] = "Les mots de passe ne correspondent pas";
            $fieldErrors['password_confirm'] = true;
        }

        if (!empty($errors)) {
            return ['status' => false, 'errors' => $errors, 'field_errors' => $fieldErrors];
        }

        return [
            'status' => true,
            'data' => [
                'prenom'        => $data['prenom'],
                'nom'           => $data['nom'],
                'email'         => $data['email'],
                'genre'         => $data['genre'],
                'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT)
            ]
        ];
    }

    public function storeHealthDraft($data)
    {
        $errors = [];
        $fieldErrors = [];

        $poids = $data['poids'] ?? null;
        $taille = $data['taille'] ?? null;
        $age = $data['age'] ?? null;

        if (!isset($poids) || !is_numeric($poids)) {
            $errors[] = "Poids invalide";
            $fieldErrors['poids'] = true;
        } elseif ((float)$poids < 20 || (float)$poids > 300) {
            $errors[] = "Poids irréaliste (doit être entre 20 et 300 kg)";
            $fieldErrors['poids'] = true;
        }

        if (!isset($taille) || !is_numeric($taille)) {
            $errors[] = "Taille invalide";
            $fieldErrors['taille'] = true;
        } elseif ((float)$taille < 50 || (float)$taille > 250) {
            $errors[] = "Taille irréaliste (doit être entre 50 et 250 cm)";
            $fieldErrors['taille'] = true;
        }

        if (!isset($age) || !is_numeric($age)) {
            $errors[] = "Âge invalide";
            $fieldErrors['age'] = true;
        } elseif ((int)$age < 10 || (int)$age > 120) {
            $errors[] = "Âge irréaliste (doit être entre 10 et 120 ans)";
            $fieldErrors['age'] = true;
        }

        if (!empty($errors)) {
            return ['status' => false, 'errors' => $errors, 'field_errors' => $fieldErrors];
        }

        return [
            'status' => true,
            'data' => [
                'poids'  => (float)$poids,
                'taille' => (float)$taille,
                'age'    => (int)$age,
            ]
        ];
    }

    public function insertUser($data)
    {
        return $this->db->table('user')->insert($data);
    }

    public function insertClient($data)
    {
        return $this->db->table('client')->insert($data);
    }
}
