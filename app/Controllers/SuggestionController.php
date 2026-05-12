<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\GoalPoidsModel;
use App\Models\RegimeModel;
use App\Models\SportModel;
use App\Models\ClientModel;

class SuggestionController extends BaseController
{
    public function index()
    {
        //
    }


    public function getSuggestionsByClient($idClient = null)
    {
        if (!$idClient) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID client manquant'
            ])->setStatusCode(400);
        }

        // Vérifier authentification et que le client est le sien
        if (!session()->has('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Non authentifié'
            ])->setStatusCode(401);
        }

        $userId = session()->get('user_id');
        $clientModel = new ClientModel();
        $goalPoidsModel = new GoalPoidsModel();
        $regimeModel = new RegimeModel();
        $sportModel = new SportModel();

        // Vérifier que le client existe et appartient à l'utilisateur
        $client = $clientModel->where('id_user', $userId)->first();
        if (!$client || $client['id'] != $idClient) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Accès refusé'
            ])->setStatusCode(403);
        }

        // Récupérer les objectifs du client
        $objectifs = $goalPoidsModel->where('client_id', $idClient)->findAll();
        if (empty($objectifs)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucun objectif défini'
            ])->setStatusCode(400);
        }

        // Calculer l'écart minimum et maximum de poids
        $poidsCibles = array_column($objectifs, 'poids_cible');
        $poidsActuel = $client['poids'];
        $poidsMin = min($poidsCibles);
        $poidsMax = max($poidsCibles);
        $poidsObjectifMoyen = array_sum($poidsCibles) / count($poidsCibles);

        // Calculer les écarts
        $ecartMin = abs($poidsActuel - $poidsMin);
        $ecartMax = abs($poidsActuel - $poidsMax);

        // Sélectionner régimes avec variation_poids proche (±10% acceptable)
        $tolerance = ($poidsActuel * 10) / 100;
        $ecartMoyen = ($ecartMin + $ecartMax) / 2;

        $db = \Config\Database::connect();
        $builder = $db->table('regime');
        $builder->where('variation_poids >=', $ecartMoyen - $tolerance);
        $builder->where('variation_poids <=', $ecartMoyen + $tolerance);
        $regimes = $builder->get()->getResultArray();

        if (empty($regimes)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Aucun régime adapté trouvé'
            ])->setStatusCode(400);
        }

        // Sélectionner le sport avec meilleur pourcentage_reduction
        $sportAvecMeilleur = $sportModel->orderBy('pourcentage_reduction', 'DESC')->first();
        $purchasedRows = $db->table('regimeclient')
            ->select('regime_id')
            ->where('client_id', $idClient)
            ->get()
            ->getResultArray();

        $purchasedSet = [];
        foreach ($purchasedRows as $row) {
            $rid = (int) ($row['regime_id'] ?? 0);
            if ($rid > 0) {
                $purchasedSet[$rid] = true;
            }
        }
        // Construire les suggestions
        $suggestions = [];
        foreach ($regimes as $regime) {
            $isPurchased = isset($purchasedSet[(int) $regime['id']]);
            $prix = $regime['prix'];
            if ($client['estGold']) {
                $prixFinal = \App\Helpers\PricingHelper::applyGoldReduction($prix, true);
            } else {
                $prixFinal = $prix;
            }

            $suggestions[] = [
                'regime' => [
                    'id' => $regime['id'],
                    'libelle' => $regime['libelle'],
                    'description' => $regime['description'],
                    'pourcentage_viande' => $regime['pourcentage_viande'],
                    'pourcentage_poisson' => $regime['pourcentage_poisson'],
                    'pourcentage_volaille' => $regime['pourcentage_volaille'],
                    'prix_original' => $prix,
                    'prix_final' => $prixFinal,
                    'reduction_appliquee' => $client['estGold'] ? 15 : 0,
                    'image' => $regime['image'],
                    'is_purchased' => $isPurchased,
                ],
                'sport' => [
                    'id' => $sportAvecMeilleur['id'],
                    'libelle' => $sportAvecMeilleur['libelle'],
                    'effet' => $sportAvecMeilleur['pourcentage_reduction'] . '%'
                ],
                'duree_jours' => round(array_sum(array_column($objectifs, 'duree')) / count($objectifs)),
                'poids_objectif' => $poidsObjectifMoyen,
                'imc_objectif' => \App\Helpers\HealthHelper::calculateIMC($poidsObjectifMoyen, $client['taille']),
                'interpretation_imc' => \App\Helpers\HealthHelper::interpretIMC(
                    \App\Helpers\HealthHelper::calculateIMC($poidsObjectifMoyen, $client['taille'])
                )
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'poids_actuel' => $poidsActuel,
                'poids_objectif' => $poidsObjectifMoyen,
                'imc_actuel' => \App\Helpers\HealthHelper::calculateIMC($poidsActuel, $client['taille']),
                'suggestions' => $suggestions,
                'nombre_suggestions' => count($suggestions)
            ]
        ]);
    }

    /**
     * Rafraîchit les suggestions pour un client
     * Appelée automatiquement après updateGoal()
     * 
     * @param int $clientId ID du client
     * @return bool
     */
    public function refreshSuggestions($clientId)
    {
        // Cette méthode peut être utilisée pour:
        // - Recalculer et cacher les suggestions
        // - Notifier le client de nouvelles suggestions
        // - Logger les changements

        // Pour l'instant, simple log
        log_message('info', "Suggestions refreshed for client: $clientId");
        return true;
    }
}
