# Règles appliquées à date

* Split des fichiers models
* Un model openapi pour une ressource REST (vs un model openapi par Dto PHP)
* indent de 4 espaces
* utilisation stricte des quotes
* pas de commit du openapi.json
    * génération dans la CI si besoin pour les tests

# A tester a l'avenir

* split par paths plutôt que split par model
* utlisation de stoplight.io (IDE pour openapi)
* Un model openapi par Dto PHP
* génération systématique en local du openapi.json
    * Validation de la fraicheur du fichier (comparaison de date de modification ?)
    * exposer le fichier dans la MR pour permettre une review simplifiée avec editor.swagger.io
    * commiter le openapi.json
