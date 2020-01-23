<?php

require 'vendor/autoload.php';

use Wf3\Article;

$params = [];

$params['id'] = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$params['titre'] = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING);
$params['corps'] = filter_input(INPUT_POST, 'corps', FILTER_SANITIZE_STRING);
$params['auteur'] = filter_input(INPUT_POST, 'auteur_id', FILTER_SANITIZE_NUMBER_INT);

//S'il s'agit d'une mise à jour.
if($params['id']){
  $article = Article::charger($params['id']);
  $article->hydrater($params);
}

//sinon c'est une création.
else
  $article = new Article($params);

//Enregistrement de l'article en BDD.
$article->enregistrer();

header("Location: index.php");
