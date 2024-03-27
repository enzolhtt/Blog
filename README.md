## Doc pour la VM du blog d'Enzo

# 1) Installation de la VM
En cas d’utilisation de **vagrant**:

1- Installation d’une VM vagrant  dans mon cas. J’ai utilisé une debian  bullseye64 :
-   Vagrant init debian/bullseye64
-   Vagrant up (pour la lancer)
-   Modifier le vagrant file pour mettre la vm en bridge. 

2- Se connecter en SSH de la VM pour pouvoir exécuter les commandes qui vont suivre
-   Vagrant  ssh 

**Sinon** suivre l’installation sur votre machine linux pour tous les packages et composants nécessaires. Se mettre en **bridge** pour pouvoir y accéder par l'adresse ip par la suite

# 2) Installation d'apache
Pour l’installation d’**apache** il faut faire les commandes suivantes :

-   sudo  apt update (met à jour les packages).
    
-   sudo  apt  install apache2 (installation du package d’apache).
    
-   sudo  systemctl  status apache2 (vérifier si le service est en cours d’exécution, c'est-à-dire, si le Active a pour attribut Active(running)).


# 3) Installation de PHP

Nous allons utiliser la version **8.2** de PHP.

-   Sudo  apt update (mettre encore les packages à jour)
    
-   Sudo apt -y install  lsb-release apt-transport-https ca-certificates
    
-  sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg

- sudo echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list

Se rendre dans le dossier apt se trouvant dans le etc, **/etc/apt** et exécuter la commande suivante :

- sudo chmod ugo+rwx sources.list.d (pour donner les droits)

Retourner à la **racine de la VM**

- sudo apt install php8.2 -y
- php -v (pour s'assurer de la bonne version de PHP installée)


# 4) Installer MariaDB

Voici les commandes pour la **bonne version de MariaDB** :

- sudo apt update
- sudo apt install mariadb-server (pour installer le package)
- sudo systemctl start mariadb.service (lancer le serveur)
- sudo mariadb (ouvre un prompte mariadb)
- CREATE USER 'enzoblog'@'%' IDENTIFIED BY 'enzoblogmdp'; (création d'un utilisateur mariadb)
- GRANT ALL ON *.* TO 'enzoblog'@'%' IDENTIFIED BY 'enzoblogmdp' WITH GRANT OPTION; (donne tous les privilèges à l'utilisateur)
- FLUSH PRIVILEGES; (pour actualiser les droits)

Ne pas oublier de changer le **bind-address** :

- sudo nano /etc/mysql/mariadb.conf.d/50-server.cnf
Mettre le bind-address = 0.0.0.0

# 5) Installation de GIT

Nous avons besoin de **git** pour pouvoir récupérer le blog se situant sur mon github

- sudo apt update
- sudo apt install git
- git --version (pour voir si git est bien installer)
- sudo apt update
- sudo apt install libz-dev libssl-dev libcurl4-gnutls-dev libexpat1-dev gettext cmake gcc (installation des packages que git utilise)

Ensuite il faut se rendre dans **/var/www/** pour faire la suite des commandes :

- sudo git clone https://github.com/enzolhtt/Blog.git

Le projet en symfony est maintenant sur votre VM, il faut maintenant indiquer ou il se trouve en réglant apache, donc pour cela on va modifier un fichier apache : 

- sudo nano /etc/apache2/sites-available/000-default.conf

 Il faut donc modifier les lignes suivantes :
- DocumentRoot /var/www/html 
EN
- DocumentRoot /var/www/Blog/public
<Directory /var/www/Blog/public>
AllowOverride None
Require all granted
Fallbackressource /index.php
</ Directory >

# 6) Installation de composer pour Symfony

Il nous faut **composer** pour que symfony fonctionne : 

- sudo apt install curl php-cli php-mbstring git unzip
- curl -sS https://getcomposer.org/installer -o composer-setup.php
- HASH=`curl -sS https://composer.github.io/installer.sig
- echo $HASH (censé avoir un retour d'un hash)
- php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" (cencé retourner "Installer verified" si tout fonctionne bien)
- sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
- composer (pour voir la version de composer et si il est bien installé)

Se placer dans le dossier du blog dans notre cas **/var/www/Blog/** et effectuer les commandes suivantes : 

- sudo chmod 777 Blog -R (pour avoir tous les droits pour installer ou modifier)
- sudo apt install php8.2-xml (pour avoir le xml de la bonne version de PHP)
- sudo apt-get install php8.2-mysql

# 7) Creation de la base de données avec Symfony

Pour **toutes** les commandes Symfony il faudra se placer dans le dossier du blog c'est-à-dire **/var/www/Blog**.
Il faut vérifier que dans **/var/www/Blog/migrations** il ne reste **aucuns** fichier de migrations sinon il faut les supprimer.
Avant d'effectuer les commandes pour créer la base de donnée il faut se rendre dans le fichier .env du projet symfony pour rentrer l'adresse ip de votre machine où vous compter créer la base de donnée:
Changer la ligne suivante en modifiant l'adresse IP en gras : DATABASE_URL="mysql://admintest:admintest@**172.16.119.27**:3306/blog?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
Effectuer ensuite les commandes suivantes :

- php bin/console doctrine:database:create (pour créer la base de données)
- php bin/console make:migration (mettre yes à ce qui est demandé)
- php bin/console doctrine:migrations:migrate (mettre yes aussi)

N'hésitez pas à redémarrer vos servers en cas de problèmes avec les commandes suivantes : 

- sudo systemctl restart apache2
- sudo systemctl restart mysql

Si vous avez des questions n'hésiter pas à me contacter :)
