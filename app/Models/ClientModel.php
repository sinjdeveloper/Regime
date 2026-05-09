<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'client';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_user', 'email', 'genre', 'dateNaissance', 'poids', 'taille', 'estGold', 'argent'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];


    // Validation
    protected $validationRules      = [
        'id_user'       => 'required|integer|greater_than[0]',
        'email'         => 'required|valid_email|is_unique[client.email]',
        'genre'         => 'required|in_list[masculin,féminin,autre]',
        'dateNaissance' => 'required|valid_date[Y-m-d]',
        'poids'         => 'required|numeric|greater_than[0]',
        'taille'        => 'required|numeric|greater_than[0]',
        'estGold'       => 'boolean',
        'argent'        => 'numeric|greater_than_equal_to[0]',
    ];
    protected $validationMessages   = [
        'email' => ['is_unique' => 'Cet email est déjà utilisé'],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

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
     * Applique la réduction Gold (15%) au prix si applicable
     *
     * @param float $prix Prix original
     * @return float Prix avec réduction si Gold, sinon prix original
     */
    public function applyGoldDiscount(float $prix): float
    {
        if ($this->estGold) {
            return round($prix * 0.85, 2);
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

        if (empty($data['prenom'])) {
            $errors[] = "Prénom requis";
        }

        if (empty($data['nom'])) {
            $errors[] = "Nom requis";
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }

        if (empty($data['genre'])) {
            $errors[] = "Genre requis";
        }

        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = "Mot de passe trop court";
        }

        if (($data['password'] ?? null) !== ($data['password_confirm'] ?? null)) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }

        if (!empty($errors)) {
            return [
                'status' => false,
                'errors' => $errors
            ];
        }

        return [
            'status' => true,
            'data' => [
                'prenom' => $data['prenom'],
                'nom' => $data['nom'],
                'email' => $data['email'],
                'genre' => $data['genre'],
                'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT)
            ]
        ];
    }
}
