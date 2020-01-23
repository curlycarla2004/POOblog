# POO & PDO

Dernière étape avant lee modèle MVC avec l'intégration de **PDO** au sein de nos classes PHP. Nous réutiliserons pour cet exercice la BDD de notre blog.

![Alt text](img/screenshot.png?raw=true "Screenshot")

## Développements

1. Implémentation de la classe **Auteur**
  * Pouvoir enregistrer / mettre à jour un nouvel auteur via une méthode **public function enregistrer()**
  * Pas besoin de formulaire, tester directement via du code.
2. Mise à jour de la classe **Article**
  * Ajout d'un attribut **$auteur** et modifier la requête de chargement pour que ce attribut soit une instance de **Auteur**
  * Mettre à jour la page index.php pour que les infos des auteurs apparaissent.
3. Implémentation de la classe **Tag**
  * Pouvoir enregistrer / mettre à jour un nouveau tag via une méthode **public function enregistrer()**
4. Mise à jour de la classe **Article**
  * Ajout d'un attribut **$tags** puis ajout d'une méthode **getTags** qui retournera un tableau d'instances de **Tag**
  * Mettre à jour la page index.php pour que les infos des tags apparaissent.

