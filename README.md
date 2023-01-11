# eolia-symfony-5.4

copier le projet:
git clone https://github.com/julienh62/eolia-symfony-5.4.git

se mettre dans le dossier du projet:
cd eolia-symfony-5.4 

pour charger les composants du vendor:
composer update 

faites une copie du fichier .env et nommer le .env.local  pour recevoir la base de donnée 

commenter la ligne ;
DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=14&charset=utf8"

décommentez la ligne ci dessous et mettez vos paramètres SQL:
(identifiant à la place de app, mdp à la place de !changeMe! et la bonne version sql):
DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8&charset=utf8mb4"
DATABASE_URL="mysql://identifiant:mot de passe@127.0.0.1:3306/app?serverVersion=numerodevotreversion&charset=utf8mb4"

pour créer la base de donnée si elle n'existe pas:
php bin/console doctrine:database:create


pour charger et/ou mettre à jour la base de données :
symfony console doctrine:schema:update --force

