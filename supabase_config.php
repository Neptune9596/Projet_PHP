<?php
require 'config/supabase_config.php';

// 1. Créer un commentaire
$commentaire = [
    'id_joueur' => 5,
    'texte' => 'Excellent joueur',
    'auteur' => 'Entraîneur'
];

$resultat = faireRequeteSupabase('POST', 'commentaires', $commentaire);
$idCommentaire = $resultat[0]['id_commentaire']; // ID du commentaire créé

// 2. Récupérer le joueur
$joueur = faireRequeteSupabase('GET', 'joueurs', null, ['id_joueur' => 5]);

// 3. Ajouter l'ID du commentaire à sa liste
$nouvelleListeCommentaires = $joueur[0]['list_commentaire'];
$nouvelleListeCommentaires[] = $idCommentaire;

// 4. Mettre à jour le joueur
$donnees = ['list_commentaire' => $nouvelleListeCommentaires];
faireRequeteSupabase('PATCH', 'joueurs?id_joueur=eq.5', $donnees);

echo "Commentaire ajouté à la liste du joueur !";
?>

<?php
// Configuration Supabase
define('SUPABASE_URL', 'https://your-project-id.supabase.co');
define('SUPABASE_API_KEY', 'your-anon-public-key-here');

// Fonction utilitaire pour faire des requêtes à Supabase
function faireRequeteSupabase($methode, $table, $donnees = null, $filtre = null) {
    $url = SUPABASE_URL . '/rest/v1/' . $table;
    
    if ($filtre) {
        $url .= '?' . http_build_query($filtre);
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . SUPABASE_API_KEY,
        'Content-Type: application/json',
    ]);
    
    if ($methode === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($donnees));
    } elseif ($methode === 'PATCH') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($donnees));
    } elseif ($methode === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    
    $reponse = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($reponse, true);
}
?>

require 'config/supabase_config.php';

// Exemple : insérer un joueur
$donnees = [
    'nom' => 'Dupont',
    'prenom' => 'Marc',
    'numero_licence' => 'LIC123456',
    'date_naissance' => '1995-05-15',
    'taille' => 180,
    'poids' => 75,
    'statut' => 'Actif'
];

$resultat = faireRequeteSupabase('POST', 'joueurs', $donnees);
```

---

## 📋 Exemple Complet : Insérer un Joueur

```php
<?php
require 'config/supabase_config.php';

// Créer les données du joueur
$joueur = [
    'nom' => 'Dupont',
    'prenom' => 'Marc',
    'numero_licence' => 'LIC123456',
    'date_naissance' => '1995-05-15',
    'taille' => 180,
    'poids' => 75,
    'statut' => 'Actif'
];

// Insérer dans la BD
$resultat = faireRequeteSupabase('POST', 'joueurs', $joueur);

if ($resultat && isset($resultat[0]['id_joueur'])) {
    echo "Joueur créé avec l'ID: " . $resultat[0]['id_joueur'];
} else {
    echo "Erreur lors de la création";
}
?>
```

---

## 📊 Exemple Complet : Récupérer Tous les Joueurs

```php
<?php
require 'config/supabase_config.php';

// Récupérer tous les joueurs
$joueurs = faireRequeteSupabase('GET', 'joueurs');

foreach ($joueurs as $j) {
    echo $j['nom'] . " " . $j['prenom'] . " (" . $j['statut'] . ")<br>";
}
?>
```

---

## 🔗 Exemple : Insérer une Participation

```php
<?php
require 'config/supabase_config.php';

// Créer une participation
$participation = [
    'id_match' => 1,
    'id_joueur' => 5,
    'poste' => 'Attaquant',
    'etat' => 'Titulaire',
    'evaluation' => 'Bon'
];

// Insérer dans la BD
$resultat = faireRequeteSupabase('POST', 'participe', $participation);
?>