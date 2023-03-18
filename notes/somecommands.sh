#  -------------------------------------------------------------------------------
#  Création ou modification d'une entité.
#  On donne son nom et on ajoute les attributs 1/1.
#  Deux classes sont crées : Une dans Entity pour les méthodes liées à l'objet
#  et tous ses attributs. Sont générés tous les getters / setters.
#  Une dans Repository pour les opérations SQL. Select, Add, Remove...
#  En cas de modification ( ajout d'attributs ) les classes sont mises à jour
#  -------------------------------------------------------------------------------
$ symfony console make:entity
#  -------------------------------------------------------------------------------
#  On génère ensuite un fichier de MAJ de la base de données. 
#  Cette étape nécessite une connexion à la base cible pour vérifier son état par rapport à 
#  celui du référentiel doctrine. Nouvelles tables, colonnes, ... ????
#  Un fichier PHP de migration est créé avec les ordres SQL nécessaires pour mettre
#  à jour la base. Il est dans le dossier migrations et son nom inclu un timestamp 
#  pour le différencier. 
#  -------------------------------------------------------------------------------
$ symfony console make:migration
$ symfony console doctrine:migrations:migrate
$ symfony console doctrine:migrations:status
#  -------------------------------------------------------------------------------
# Quelques commandes doctrine migrations : 
#  -------------------------------------------------------------------------------
      doctrine:migrations:current
      doctrine:migrations:diff
      doctrine:migrations:dump-schema
      doctrine:migrations:execute
      doctrine:migrations:generate
      doctrine:migrations:latest
      doctrine:migrations:list
      doctrine:migrations:migrate
      doctrine:migrations:rollup
      doctrine:migrations:status
      doctrine:migrations:sync-metadata-storage
      doctrine:migrations:up-to-date
      doctrine:migrations:version

# Créer un(e) fixture
$ symfony console make:fixture
# -------------------------------------------------------------------------------
# Editer la classe crée et ajouter le  code applicatif.
# Puis lancer le programme depuis symfony.
# load est le nom de la méthode qui fait le boulot dans la classe Fixture
# -------------------------------------------------------------------------------
$ symfony console doctrine:fixture:load
# Ou bien
$ symfony console doctrine:fixture:load --append
# -------------------------------------------------------------------------------
# Ajout d'une form, qui va être rattachée à une entité
# Le même résultat peut être obtenu par programmation avec l'API
# -------------------------------------------------------------------------------
$ symfony console make:form
# -------------------------------------------------------------------------------
# Migrate new entity attributes
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
# -------------------------------------------------------------------------------
# Migrations status
$ php bin/console doctrine:migrations:status
# -------------------------------------------------------------------------------
# Undo a migration
$ php bin/console doctrine:migrations:execute DoctrineMigrations\Version20230318092754 --down
# -------------------------------------------------------------------------------
# Delete a migration
# The operation takes place in the version table of the supporting DB
# Does not take care of the migration file. 
# Delete it manually otherwise the doctrine status will be wrong
# Also cleanup any modification in the entity related to the attributes used in the migratioun file
$ php bin/console doctrine:migrations:version Version20230318092754 --delete
