# Migrate new entity attributes
php bin/console make:migration
php bin/console doctrine:migrations:migrate
# Migrations status
php bin/console doctrine:migrations:status
# undo a migration
php bin/console doctrine:migrations:execute DoctrineMigrations\Version20230318092754 --down
# Delete a migration
# The operation takes place in the version table of the supporting DB
# Does not take care of the migration file. 
# Delete it manually otherwise the doctrine status will be wrong
# Also cleanup any modification in the entity related to the attributes used in the migratioun file
php bin/console doctrine:migrations:version Version20230318092754 --delete
