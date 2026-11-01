# Sultan — site restaurant (PHP natif / MySQL / MVC léger)

## Installation

1. Importer `sql/schema.sql` dans MySQL.
2. Renseigner `app/config.php`.
3. Lancer : `php -S localhost:8000 -t public public/index.php` (ou Apache avec mod_rewrite sur `public/`).
4. Compléter `settings` (téléphone, adresse, WhatsApp, maps_embed), `gallery`, `reviews`.

**Le site et l'administration sont intégralement en italien** (interface, contenus, catégories et
plats de démonstration, `lang="it"`, dates en italien).

**Base déjà installée ?** Importer une seule fois, dans cet ordre :

| Fichier | Rôle |
| --- | --- |
| `sql/migration_admin.sql` | crée `daily_special` et `login_attempts`, ajoute les textes éditables |
| `sql/migration_it.sql` | traduit en italien les catégories, plats, légendes de galerie et textes de la maison |

Les deux migrations sont conditionnelles : les plats ou catégories que vous avez déjà renommés
sont conservés, et les textes ne sont remplacés que s'ils sont encore dans leur version d'origine.

## Espace d'administration

Ouvrir **`/admin`** dans le navigateur.

- Au premier accès, un assistant crée le compte du gérant (e-mail + mot de passe de 10 caractères
  minimum, stocké haché en bcrypt). Aucun identifiant par défaut n'est livré.
- En ligne de commande : `php tools/admin-password.php email@exemple.com 'mot-de-passe'`.

### Ce que l'administration permet

| Section | Actions |
| --- | --- |
| Tableau de bord | plats, réservations à traiter, messages, visites 14 jours, répartition de la carte |
| La carte | créer / modifier / supprimer un plat, prix, description, composition, allergènes, photo, disponibilité, mise en avant |
| Catégories | créer / renommer / supprimer, réordonner (les plats d'une catégorie supprimée sont confirmés explicitement) |
| Plat du jour | nom, prix, composition, description, allergènes, photo, date de service, publication / retrait |
| Informations | nom, accroche, description, texte « notre histoire », téléphone, WhatsApp, e-mail, adresse, coordonnées GPS, carte Google Maps, note de la carte, citation |
| Horaires | deux services par jour et jour de fermeture |
| Réseaux sociaux | ajouter / modifier / supprimer les liens |
| Galerie | téléverser, remplacer, légender, ordonner, supprimer |
| Demandes | réservations (confirmer / annuler / supprimer), messages, inscrits à la newsletter + export CSV |
| Avis clients | ajouter, corriger, publier / masquer, supprimer |
| Mon compte | changer l'e-mail et le mot de passe |

Les images téléversées sont enregistrées dans `public/assets/uploads/` (JPG, PNG ou WEBP, 5 Mo
maximum, type contrôlé par `getimagesize`). Un `.htaccess` y interdit l'exécution de scripts.

### Sécurité

- Sessions dédiées, cookie `HttpOnly` + `SameSite=Lax`, régénération d'identifiant à la connexion.
- Jeton CSRF sur tous les formulaires de l'administration.
- 8 tentatives de connexion maximum par quart d'heure (par adresse et par e-mail), journalisées
  dans `login_attempts`.
- Pages `/admin` en `noindex, nofollow`, `Cache-Control: no-store`, et `Disallow: /admin` dans
  `robots.txt`.
- Toutes les requêtes d'écriture utilisent des requêtes préparées ; les noms de colonnes triables
  sont limités à une liste blanche.

## Structure

```
app/Controllers/AdminController.php   routes, authentification, CRUD
app/views/admin/                      mise en page + pages de l'administration
public/assets/css/admin.css           charte de l'administration (encre, ivoire, or)
public/assets/js/admin.js             interactions (menu mobile, confirmations, aperçus)
sql/migration_admin.sql               migration technique pour une base existante
sql/migration_it.sql                  passage des contenus en italien
tools/admin-password.php              création / réinitialisation d'un compte
```
