# Changelog

Toutes les modifications notables apportées à ce projet seront documentées dans ce fichier.

## [1.0.2] - 2026-02-02

### Correctifs de bugs
- src/Controller/SecurityController.php & RegisterController.php : Uniformisation de la cle de session $_SESSION['user']['firstname'] pour correspondre a l'appel dans le dashboard et eviter l'erreur "Undefined array key".

## [1.0.1] - 2026-02-02

### Securite (Correctifs critiques)
- src/Repository/ (UserRepository, HabitRepository, HabitLogRepository) : Remplacement de l'interpolation de variables dans les chaines SQL par des requetes preparees (PDO::prepare + execute) pour bloquer les injections SQL.
- src/Repository/UserRepository.php : Ajout de password_hash() dans la methode insert() pour supprimer le stockage des mots de passe en clair.
- src/Controller/SecurityController.php : Utilisation de password_verify() pour authentifier les utilisateurs via leurs empreintes de hash, protegeant ainsi les identifiants en cas de fuite de base de donnees.
- templates/ (admin/user/new, register/index, security/login) : Encapsulation des sorties dynamiques ($error, $user) dans htmlspecialchars() pour neutraliser les injections de scripts malveillants (XSS).
- config/routes.json : Ajout de AdminGuard sur les routes /admin/user pour interdire l'acces aux fonctions de gestion d'utilisateurs aux comptes non privilegies.

### Correctifs de bugs
- public/index.php & public/.user.ini : Utilisation de __DIR__ et extension de open_basedir dans la configuration PHP locale pour autoriser l'inclusion de vendor/autoload.php, resolvant le blocage du serveur en production.
- src/Controller/RegisterController.php : Correction du test de condition (changement de $_GET['user'] vers $_POST['user']) pour permettre la validation effective du formulaire d'inscription.
- src/Controller/Member/HabitsController.php : Rectification de l'URL de redirection vers /habits (au lieu de /habit) pour eviter une erreur 404 apres la creation d'une habitude.
- src/Controller/RegisterController.php : Mise a jour de la redirection vers /dashboard (au lieu de /user/ticket, route inexistante) pour finaliser correctement le parcours d'inscription.
- src/Controller/Api/HabitsController.php : Renommage de la classe de HabitController vers HabitsController pour respecter le mapping du routeur et corriger l'echec des appels API.
- src/Controller/SecurityController.php : Ajout d'instructions exit apres header('Location: ...') pour forcer l'arret de l'execution du script et securiser les redirections.

### Deploiement et Documentation
- deploy-hook.sh : Script automatise pour trigger le deploiement via Webhooks aaPanel (pull git, migration DB).
- install_7.0_en.sh : Script d'installation environnement PHP/Nginx mis a jour pour la production.
- demo_data.sql : Script SQL complet pour peupler la base de donnees avec un environnement de test fonctionnel.
- ARCHITECTURE.md, DEPLOY.md, API.md, QUESTIONS.md : Documentation technique detaillee de la structure MVC, des points API et des procedures serveur.

### Ameliorations
- src/Repository/HabitRepository.php : Implementation de getStreak() calculant les jours consecutifs de completion pour gamifier l'experience utilisateur.

## [0.1.0] - 2026-02-01

### Initial Build
- Structure MVC de base et moteur de templates PHP.
- Systeme de routage via routes.json.
- CRUD initial des habitudes et gestion de base des utilisateurs.
