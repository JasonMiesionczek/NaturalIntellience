#!/bin/bash
CONSOLE="php app/console"
entities()
{
    $CONSOLE doctrine:generate:entities AppBundle --no-backup
}

entity()
{
    $CONSOLE doctrine:generate:entity
}

schema()
{
    $CONSOLE doctrine:schema:update --force
}

case "$1" in
    "gen:all" )
        entities
        schema
        ;;
    "gen:entities" )
        $CONSOLE doctrine:generate:entities AppBundle --no-backup
        ;;
    "gen:entity" )
        $CONSOLE doctrine:generate:entity
        ;;
    "schema:update" )
        $CONSOLE doctrine:schema:update --force
        ;;
    *)
        echo "no command specified"
        echo
        echo "gen:entities"
        echo "gen:entity"
        echo "schema:update"
        exit 1
        ;;
esac
