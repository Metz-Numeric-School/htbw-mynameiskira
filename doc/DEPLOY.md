# Procédure de Déploiement

Décrivez ci-dessous votre procédure de déploiement en détaillant chacune des étapes. De la préparation du VPS à la méthodologie de déploiement continu.
# PROCÉDURE

**Etape** 1 : Se connecter en SSH a Debian pour tester la connexion  
Noter : Adresse IP, nom de domaine

Terminal : Windows ssh root@[adresse_id]

**Etape** 2 : Récupérer le code source de l’application existante  
On a un repo git pour récupérer le projet et sur lequel on doit faire nos push (ce git sera évalué)

Terminal : VSCode git clone [adresse_https_donné_par_github]

**Etape** 3 : Installer les dépendances  
Terminal : VSCode composer install --optimize-autoloader

**Etape** 4 : Vérifier que le fichier fonctionne sur notre PC (tout est expliqué dans le Readme)  
Terminal : VSCode Copier le fichier ".env.sample" et le renommer en ".env"

Terminal : VSCode php bin/serve

**Etape** 5 : Installer GitCliff  
Terminal : VSCode brew install git-cliff

Terminal : VSCode git-cliff --init

Terminal : VSCode git add .

Terminal : VSCode git commit -m "doc:First commit"

Terminal : VSCode git-cliff --bump -o ./CHANGELOG.md

Voir la version générée dans CHANGELOG.mg

Terminal : VSCode git add .

Terminal : VSCode git commit -m "version [tag]"

Terminal : VSCode git tag [tag]

Terminal : VSCode git push origin main

**Etape** 6 : Installer aaPanel  
Terminal : Windows URL=https://www.aapanel.com/script/install_7.0_en.sh && if [ -f /usr/bin/curl ];then curl -ksSO "$URL" ;else wget --no-check-certificate -O install_7.0_en.sh "$URL";fi;bash install_7.0_en.sh aapanel

Site web de aaPanel avec le lien

Terminal : Windows su - (pour passer en root si besoin)

Laisser l’installation se faire (profiter pour faire la doc)

Noter : Identifiant, mot de passe donné, 2ème adresse IP

Navigateur : Ouvrir la 2ème adresse IP

VPS : libérer les ports :

Navigateur : Forcer l’ouverture de la page non sécurisée

Navigateur : Se connecter avec les identifiants donnés

Navigateur : Sélectionner LNMP (One-click)

Laisser l’installation se faire (profiter pour faire la doc)

Navigateur : Se rendre dans l’onglet "Website" à gauche

Navigateur : Cliquer sur le bouton "Add site"

Navigateur : Noter le nom de domaine fournis par MNS dans "Domaine name"

Navigateur : Sélectionner "Create" sur le champ "FTP"

Noter : FTP settings, Password
ftp_anouar_dfs_lan
2f6c66e19b5eb8

Navigateur : Sélectionner "MySQL" sur le champ "Database"

Noter : Database settings, Password
sql_anouar_dfs_lan
f2f48583d03d6

"Database settings" sera le nom et l’identifiant de la BDD

"Password" sera le mot de passe de la BDD

Navigateur : Cliquer sur le bouton "Confirm"

Navigateur : Fermer la modale avec les identifiants

**Etape** 7 : Déployer  
Terminal : Windows cd /

Terminal : Windows ls /www/wwwroot

Vérifier qu’il y a bien un fichier avec l’IP fournis par MNS

Terminal : Windows mkdir /var/depot_git

Terminal : Windows cd /var/depot_git

Terminal : Windows git init --bare

Terminal : VSCode git remote add vps root@[adresse_ip]:/var/depot_git

Terminal : VSCode git push -u vps [tag]

Terminal : Windows cd /

Terminal : Windows touch deploy.sh

Terminal : Windows nano deploy.sh

Terminal : Windows (dans deploy.sh) git --work-tree=/www/wwwroot/[adresse_ip] --git-dir=/var/depot_git checkout -f $1

Terminal : Windows Quitter le fichier (ctrl+X, O, Entrer)

Terminal : Windows bash /deploy.sh [tag]

**Etape** 8 : Configurer aaPanel  
Navigateur : Sélectionner le site en cliquant sur l'adresse IP

Navigateur : Se rendre dans l’onglet "Site directory"
Navigateur : Noter "/public" sur le champ "Running directory"
Navigateur : Désactiver le "XSS attack"

Navigateur : Se rendre dans l’onglet "URL Rewrite"
Navigateur : Sélectionner le profil "laravel" ou ajouter le code suivant :
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```
Navigateur : Cliquer sur "Save"

Navigateur : Se rendre dans l’onglet "SSL"
Navigateur : Sélectionner "Let’s Encrypt"
Navigateur : Cocher le nom de domaine
Navigateur : Cliquer sur le bouton "Apply"

Navigateur : Se rendre dans l’onglet "Composer"
Navigateur : Fermer la modale

**Etape** 9 : Variables d'environements  
Navigateur : Dans "Files", ouvrir le dossier du site, créer un fichier ".env"
Navigateur : Remplir avec les informations suivantes :
- DB_HOST="localhost"
- DB_PORT="3306"
- DB_DATABASE="[votre_nom_de_bdd]"
- DB_USERNAME="[votre_nom_d_utilisateur]"
- DB_PASSWORD="[votre_mot_de_passe]"
  Navigateur : Enregistrer et fermer le fichier

**Etape** 10 : Configurer le système de backup de la base de données  
Navigateur : Se rendre dans l'onglet "Database" dans le menu de gauche

Navigateur : Cliquer sur le nom de la base de données créée précédemment

Navigateur : Cliquer sur l'icône "Backup" (ou "Manage")

Navigateur : Dans la section "Backup", cliquer sur "Add scheduled backup"

Navigateur : Configurer la sauvegarde automatique :
- Type de sauvegarde : Sélectionner "Database backup"
- Nom de la base : Sélectionner la base de données du projet
- Période de sauvegarde : Sélectionner "Daily" (quotidien) ou selon les besoins
- Heure de sauvegarde : Choisir une heure creuse (ex: 03:00)
- Nombre de sauvegardes à conserver : 7 (pour garder une semaine)

Navigateur : Configurer le stockage des backups :
- Stockage local : /www/backup/database
- (Optionnel) Stockage distant : Configurer FTP/SFTP ou cloud storage si disponible

Navigateur : Cliquer sur "Submit" pour valider la configuration

Navigateur : Vérifier que la tâche planifiée apparaît dans "Cron"

Navigateur : (Optionnel) Effectuer un backup manuel pour tester :
- Retourner dans "Database"
- Cliquer sur "Backup" à côté de la base de données
- Vérifier que le fichier .sql.gz apparaît dans la liste des backups

Noter : Emplacement des backups, fréquence de sauvegarde

Terminal : Windows (Vérifier les backups) ls -lh /www/backup/database

**Etape** 11 : Chaque commit (après chaque résolution de bug)  
Terminal : VSCode git add .

Liste des mots clés dans cliff.toml

Terminal : VSCode git commit -m "[mot_clé]:[commentaire]"

Terminal : VSCode git push origin main

**Etape** 12 : Commit avant chaque déploiement (après la résolution de tout les bugs)  
Terminal : VSCode git add .

Liste des mots clés dans cliff.toml

Terminal : VSCode git commit -m "[mot_clé]:[commentaire]"

Terminal : VSCode git-cliff --bump -o ./CHANGELOG.md

Voir la version générée dans CHANGELOG.mg

Terminal : VSCode git add .

Terminal : VSCode git commit -m "version [tag]"

Terminal : VSCode git tag [tag]

Terminal : VSCode git push origin main

Terminal : VSCode git push vps [tag]

Terminal : Windows bash /deploy.sh [tag]  
