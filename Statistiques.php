<?php

class Statistiques {
    private static $pdo;

    public static function setPdo($pdo) {
        self::$pdo = $pdo;
    }

    /**
     * Stats globales de l'équipe
     */
    public static function getGlobalStats() {
        $stmt = self::$pdo->query("SELECT resultat FROM Matchs WHERE resultat IS NOT NULL AND resultat != ''");
        $matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stats = ['gagnes' => 0, 'nuls' => 0, 'perdus' => 0, 'total' => 0];

        foreach ($matchs as $m) {
            // sscanf extrait directement les nombres du format "X-Y"
            if (sscanf($m['resultat'], "%d-%d", $equipe, $adversaire) === 2) {
                $stats['total']++;
                if ($equipe > $adversaire) $stats['gagnes']++;
                elseif ($equipe < $adversaire) $stats['perdus']++;
                else $stats['nuls']++;
            }
        }
        
        // CORRECTION ICI : Calcul des pourcentages pour TOUS les résultats
        if ($stats['total'] > 0) {
            $stats['pourcentage_gagnes'] = round(($stats['gagnes'] / $stats['total']) * 100, 1);
            $stats['pourcentage_nuls'] = round(($stats['nuls'] / $stats['total']) * 100, 1);     // Ajouté
            $stats['pourcentage_perdus'] = round(($stats['perdus'] / $stats['total']) * 100, 1); // Ajouté
        } else {
            // Sécurité pour éviter la division par zéro
            $stats['pourcentage_gagnes'] = 0;
            $stats['pourcentage_nuls'] = 0;
            $stats['pourcentage_perdus'] = 0;
        }
        
        return $stats;
    }

    /**
     * Récupère les stats pour TOUS les joueurs
     */
    public static function getPlayersFullStats() {
        $stmt = self::$pdo->query("SELECT id_joueur, nom, prenom, statut FROM Joueur");
        $joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $resultats = [];

        foreach ($joueurs as $j) {
            $id = $j['id_joueur'];
            
            $j['nb_titulaire'] = self::countEtat($id, 'titulaire');
            $j['nb_remplacant'] = self::countEtat($id, 'remplaçant'); // ou 'remplacant' selon ta BDD
            $j['moyenne_eval'] = self::getMoyenne($id);
            $j['poste_prefere'] = self::getPostePrefere($id);
            
            // CORRECTION ICI : Noms de clés en français pour correspondre à Stats.php
            $j['taux_victoire'] = self::getPlayerWinRate($id); 
            $j['selection_consecutive'] = self::getConsecutiveSelections($id);

            $resultats[] = $j;
        }
        return $resultats;
    }

    // --- SOUS-FONCTIONS ---

    private static function countEtat($id_joueur, $etat) {
        $sql = "SELECT COUNT(*) FROM Participation WHERE id_joueur = ? AND etat = ?";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$id_joueur, $etat]);
        return $stmt->fetchColumn();
    }

    private static function getMoyenne($id_joueur) {
        $sql = "SELECT AVG(evaluation) FROM Participation WHERE id_joueur = ? AND evaluation IS NOT NULL";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$id_joueur]);
        $val = $stmt->fetchColumn();
        return $val ? round($val, 1) : 0;
    }

    private static function getPostePrefere($id_joueur) {
        $sql = "SELECT poste FROM Participation WHERE id_joueur = ? 
                GROUP BY poste ORDER BY COUNT(*) DESC LIMIT 1";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$id_joueur]);
        return $stmt->fetchColumn() ?: "Aucun";
    }

    private static function getPlayerWinRate($id_joueur) {
        // On récupère les résultats des matchs où le joueur a participé
        $sql = "SELECT m.resultat FROM Matchs m 
                INNER JOIN Participation p ON m.id_match = p.id_match 
                WHERE p.id_joueur = ? AND m.resultat IS NOT NULL";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$id_joueur]);
        $matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = count($matchs);
        if ($total == 0) return 0;

        $gagnes = 0;
        foreach ($matchs as $m) {
            if (sscanf($m['resultat'], "%d-%d", $equipe, $adversaire) === 2) {
                if ($equipe > $adversaire) $gagnes++;
            }
        }
        return round(($gagnes / $total) * 100, 1);
    }

    public static function getConsecutiveSelections($id_joueur) {
        $sql = "SELECT p.etat FROM Participation p 
                INNER JOIN Matchs m ON p.id_match = m.id_match 
                WHERE p.id_joueur = ? 
                ORDER BY m.date_match DESC, m.heure DESC";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute([$id_joueur]);
        $etats = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $consecutive = 0;
        foreach ($etats as $etat) {
            // Attention : assure-toi que 'titulaire' est écrit exactement comme dans ta BDD (minuscule/majuscule)
            if (strtolower($etat) === 'titulaire') {
                $consecutive++;
            } else {
                break;
            }
        }
        return $consecutive;
    }
}