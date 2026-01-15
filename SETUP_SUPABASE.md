# 🚀 Setup Supabase pour l'Application Football

## 📋 Table des Matières
1. [Configuration Supabase](#configuration-supabase)
2. [Schéma de Base de Données](#schéma-de-base-de-données)
3. [Commandes SQL](#commandes-sql)
4. [Configuration PHP](#configuration-php)

---

## ⚙️ Configuration Supabase

### Étape 1 : Créer un compte Supabase
1. Allez sur https://supabase.com
2. Cliquez sur "Start your project"
3. Créez un compte (Google, GitHub ou email)

### Étape 2 : Créer un projet
1. Une fois connecté, cliquez sur "New Project"
2. Donnez un nom à votre projet (ex: "Football-App")
3. Créez un mot de passe sécurisé pour la base de données
4. Sélectionnez votre région
5. Cliquez sur "Create new project"

### Étape 3 : Récupérer vos identifiants
1. Une fois le projet créé, allez dans **Settings** → **API**
2. Notez les informations suivantes :

**À copier :**
```
Project URL: https://mtkoiekqzumqjukqrvlv.supabase.co

Anon Key (public): "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im10a29pZWtxenVtcWp1a3Fydmx2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3Njc3OTAyMzUsImV4cCI6MjA4MzM2NjIzNX0.4993GYOOxX5rY5c-JZhyLLiCUhwh1pkgTZFLLxZKHXk"

Service Role Key: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im10a29pZWtxenVtcWp1a3Fydmx2Iiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2Nzc5MDIzNSwiZXhwIjoyMDgzMzY2MjM1fQ.tOCF2JVP_ByUqtsFukzi4fMk264G9SJjbLmh4Wm5G4g"
```

---

## 📊 Schéma de Base de Données

### Vue d'ensemble

```
JOUEURS (1) ─── (N) COMMENTAIRES
   │
   ├─ ListCommentaire (array)
   ├─ IdJoueur (PK)
   ├─ Nom
   ├─ Prenom
   ├─ NumeroLicence
   ├─ DateNaissance
   ├─ Taille
   ├─ Poids
   └─ Statut

PARTIE (1) ─── (N) PARTICIPE
   │
   ├─ IdMatch (PK)
   ├─ DateMatch
   ├─ HeureMatch
   ├─ NomAdversaire
   ├─ Adresse
   ├─ Resultat
   └─ LieuDeRecontre

PARTICIPE (N) ─── (1) JOUEUR
   │
   ├─ IdMatch (FK)
   ├─ IdJoueur (FK)
   ├─ Poste
   ├─ Etat
   └─ Evaluation
```

---

## 🗄️ Commandes SQL

### ⚠️ IMPORTANT
Ces commandes doivent être exécutées dans **Supabase SQL Editor**. Voici comment :

1. Allez dans votre projet Supabase
2. Cliquez sur **SQL Editor** (colonne de gauche)
3. Cliquez sur **New Query**
4. Copiez-collez les commandes SQL ci-dessous
5. Cliquez sur **RUN**

---

### 1️⃣ Créer la table JOUEURS

```sql
CREATE TABLE IF NOT EXISTS joueurs (
    id_joueur SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    numero_licence VARCHAR(20) UNIQUE NOT NULL,
    date_naissance DATE NOT NULL,
    taille INT NOT NULL,
    poids INT NOT NULL,
    statut VARCHAR(50) NOT NULL DEFAULT 'Actif',
    list_commentaire INT[] DEFAULT ARRAY[]::INT[]
);

-- Créer un index pour les recherches rapides
CREATE INDEX idx_joueurs_licence ON joueurs(numero_licence);
CREATE INDEX idx_joueurs_statut ON joueurs(statut);
```

**Colonnes mappées à la classe Joueur :**
- `id_joueur` ← IdJoueur
- `nom` ← Nom
- `prenom` ← Prenom
- `numero_licence` ← NumeroLicence
- `date_naissance` ← DateNaissance
- `taille` ← Taille
- `poids` ← Poids
- `statut` ← Statut
- `list_commentaire` ← ListCommentaire (array d'IDs de commentaires)

---

### 2️⃣ Créer la table PARTIES (Matches)

```sql
CREATE TABLE IF NOT EXISTS parties (
    id_match SERIAL PRIMARY KEY,
    date_match DATE NOT NULL,
    heure_match TIME,
    nom_adversaire VARCHAR(100) NOT NULL,
    adresse TEXT,
    resultat TEXT,
    lieu_rencontre VARCHAR(100)
);

-- Créer des index pour les recherches
CREATE INDEX idx_parties_date ON parties(date_match);
```

**Colonnes mappées à la classe Partie :**
- `id_match` ← IdMatch
- `date_match` ← DateMatch
- `heure_match` ← HeureMatch
- `nom_adversaire` ← NomAdversaire
- `adresse` ← Adresse
- `resultat` ← Resultat
- `lieu_rencontre` ← LieuDeRecontre

---

### 3️⃣ Créer la table PARTICIPE

```sql
CREATE TABLE IF NOT EXISTS participe (
    id_match INT NOT NULL,
    id_joueur INT NOT NULL,
    poste VARCHAR(50) NOT NULL,
    etat VARCHAR(50) NOT NULL,
    evaluation TEXT,    
    -- Clés étrangères
    CONSTRAINT fk_participe_match FOREIGN KEY (id_match) 
        REFERENCES parties(id_match),
    CONSTRAINT fk_participe_joueur FOREIGN KEY (id_joueur) 
        REFERENCES joueurs(id_joueur)
);

-- Créer des index pour les recherches
CREATE INDEX idx_participe_match ON participe(id_match);
CREATE INDEX idx_participe_joueur ON participe(id_joueur);
```

**Colonnes mappées à la classe Participe :**
- `id_participe` ← (généré auto)
- `id_match` ← IdMatch
- `id_joueur` ← IdJoueur
- `poste` ← Poste
- `etat` ← Etat
- `evaluation` ← Evaluation

---

### 4️⃣ Créer la table COMMENTAIRES

```sql
CREATE TABLE IF NOT EXISTS commentaires (
    id_commentaire SERIAL PRIMARY KEY,
    contenu TEXT NOT NULL,
    date_commentaire DATE NOT NULL,
);

-- Créer un index
CREATE INDEX idx_commentaires_joueur ON commentaires(id_joueur);
```

**Colonnes mappées à la classe Joueur :**
- `id_commentaire` ← (pour ListCommentaire)
- `id_joueur` ← (pour lier à Joueur)
- `texte` ← (contenu du commentaire)
- `auteur` ← (qui a écrit)

---

## 📝 Gestion de la Liste de Commentaires

### Structure

Chaque joueur a une colonne `list_commentaire` qui est un **array d'entiers** contenant les IDs des commentaires qui lui appartiennent.

**Exemple :**
```
Un joueur avec ID 5 peut avoir : list_commentaire = [1, 3, 7, 12]
Cela signifie qu'il a 4 commentaires avec les IDs 1, 3, 7 et 12 dans la table commentaires
```

### Ajouter un Commentaire à un Joueur

Lorsque vous créez un nouveau commentaire pour un joueur, vous devez :

1. **Insérer le commentaire** dans la table `commentaires`
2. **Ajouter son ID** à la liste `list_commentaire` du joueur

**Exemple en SQL :**
```sql
-- Étape 1 : Insérer le commentaire
INSERT INTO commentaires (id_joueur, texte, auteur)
VALUES (5, 'Excellent joueur', 'Entraîneur')
RETURNING id_commentaire;
-- Cela retourne l'ID du commentaire créé (par exemple 42)

-- Étape 2 : Ajouter cet ID à la liste du joueur
UPDATE joueurs
SET list_commentaire = array_append(list_commentaire, 42)
WHERE id_joueur = 5;
```

### Récupérer Tous les Commentaires d'un Joueur

```sql
-- Récupérer un joueur avec sa liste de commentaires
SELECT * FROM joueurs WHERE id_joueur = 5;

-- Cela retourne :
-- id_joueur | nom    | prenom | ... | list_commentaire
-- 5         | Dupont | Marc   | ... | {1,3,7,12}

-- Puis récupérer les détails des commentaires
SELECT * FROM commentaires WHERE id_commentaire = ANY(
    SELECT list_commentaire FROM joueurs WHERE id_joueur = 5
);
```

### Supprimer un Commentaire d'un Joueur

```sql
-- Supprimer le commentaire avec l'ID 3 de la liste du joueur 5
UPDATE joueurs
SET list_commentaire = array_remove(list_commentaire, 3)
WHERE id_joueur = 5;

-- Puis supprimer le commentaire de la table commentaires
DELETE FROM commentaires WHERE id_commentaire = 3;
```

### Vérifier si un Commentaire est dans la Liste

```sql
-- Vérifier si le commentaire 7 appartient au joueur 5
SELECT * FROM joueurs 
WHERE id_joueur = 5 AND 7 = ANY(list_commentaire);
```

### En PHP avec Supabase

```php
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
```

### Alternative : Requête Directe SQL dans Supabase

Si tu veux faire tout en une seule requête SQL dans Supabase :

```sql
-- Ajouter un commentaire et le mettre dans la liste du joueur en une seule transaction
WITH nouveau_commentaire AS (
    INSERT INTO commentaires (id_joueur, texte, auteur)
    VALUES (5, 'Bon match aujourd\'hui', 'Coach')
    RETURNING id_commentaire
)
UPDATE joueurs
SET list_commentaire = array_append(
    list_commentaire, 
    (SELECT id_commentaire FROM nouveau_commentaire)
)
WHERE id_joueur = 5;
```

---

### Fichier de Configuration Recommandé

Créez un fichier `config/supabase_config.php` :

```php
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
```

Puis utilisez-le dans vos classes :
```php
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
```

---

## 🧪 Test de Connexion

Pour vérifier que tout fonctionne :

```php
<?php
require 'config/supabase_config.php';

// Test simple
$resultat = faireRequeteSupabase('GET', 'joueurs');

if ($resultat !== false && $resultat !== null) {
    echo "✅ Connexion à Supabase réussie !";
    echo "<pre>" . json_encode($resultat, JSON_PRETTY_PRINT) . "</pre>";
} else {
    echo "❌ Erreur de connexion à Supabase";
}
?>
```

---

## ⚠️ Erreurs Communes

### "Invalid API Key"
- Vérifiez que vous avez bien copié la clé **Anon public** (pas la Service Role Key)
- Assurez-vous qu'elle est dans les bonnes guillemets

### "Table does not exist"
- Vérifiez que vous avez bien exécuté les commandes SQL dans Supabase
- Assurez-vous que le nom de la table est exact (minuscules)

### "CORS Error"
- Les requêtes doivent venir d'une origine autorisée
- Dans Supabase, allez dans **Settings** → **API** → **CORS**
- Ajoutez `http://localhost` et `http://127.0.0.1`

---

## 🔒 Sécurité

### À FAIRE :
✅ Utilisez Row Level Security (RLS) dans Supabase
✅ Limitez les permissions par utilisateur
✅ Ne partagez JAMAIS votre clé API en production

### À NE PAS FAIRE :
❌ Ne mettez pas votre clé API dans le code public
❌ N'exposez pas votre Service Role Key au client
❌ Ne permettez pas l'accès sans authentification

---

## 📚 Ressources

- Documentation Supabase : https://supabase.com/docs
- API REST Reference : https://supabase.com/docs/reference/api/introduction
- Authentification : https://supabase.com/docs/reference/auth

---

**Besoin d'aide ?** Consulte la documentation officielle de Supabase.
