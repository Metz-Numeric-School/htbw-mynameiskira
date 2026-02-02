# Questions

Répondez ici aux questions théoriques en détaillant un maximum vos réponses :

**1) Expliquer la procédure pour réserver un nom de domaine chez OVH avec des captures d'écran (arrêtez-vous au paiement) :**

1 - accéder au site ovhcloud.com
2 - survoler "domaine d'hébergement email voip"
3 - cliquer sur nom de domaine
4 - cliquer sur la barre à remplir "trouver votre nom de domaine"
5 - écrire le nom de domaine souhaité 
6 - cliquer sur rechercher
7 - cliquer sur "acheter" sur le nom de domaine qui nous convient
8 - une fois ajouté , cliquer sur "poursuivre la commande"
9 - ajouter options si souhaité 
10 - cliquer encore une fois pour poursuivre
11 - se connecter ou créer un compte 
12 - cliquer sur payer 

**2. Comment faire pour qu'un nom de domaine pointe vers une adresse IP spécifique ?**

Si on utilise aaPanel :
Une fois le domaine ajouté dans l'onglet Website de aaPanel, il faut s'assurer que l'enregistrement A dans la zone DNS (chez OVH) pointe vers l'IP du serveur aaPanel. Si on utilise le plugin DNS Manager de aaPanel, il faut configurer les Glue Records chez le registrar pour pointer vers aaPanel.

Si on fait à la main :
Il faut se rendre dans l'interface d'administration du bureau d'enregistrement (ex: OVH), accéder à la Zone DNS du domaine, et modifier ou ajouter un enregistrement de type A. Dans le champ cible, il faut renseigner l'adresse IP du serveur de destination.

**3. Comment mettre en place un certificat SSL ?**

Si on utilise aaPanel :
Dans la liste des sites (Website), cliquer sur le nom du site, aller dans la section SSL, sélectionner Let's Encrypt, cocher les domaines concernés et cliquer sur Apply. aaPanel s'occupe de la vérification, de la génération et du renouvellement automatique.

Si on fait à la main :
Il faut installer Certbot sur le serveur. On utilise ensuite une commande comme certbot --nginx ou certbot --apache pour générer le certificat via Let's Encrypt. Il faut ensuite configurer manuellement les blocs serveurs (VirtualHosts) pour pointer vers les fichiers fullchain.pem et privkey.pem et activer le port 443.
