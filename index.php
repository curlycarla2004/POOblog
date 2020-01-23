<?php

//Il faut toujours inclure l'autoload.
require 'vendor/autoload.php';

//Nous listons ici les classes dont nous avons besoin dans index.php.
use Wf3\Article;

//Récupération de tous les articles.
$articles = Article::chargerPlusieurs();

?>
<!DOCTYPE html>
<html lang="fr">
<?php require_once('include/head.php'); ?>

<body class="bg-dark">

  <main class="container py-5 bg-white shadow">
    <h1 class="text-center text-primary ">POO Blog</h1>
    <a href="form-article.php" class="btn btn-outline-success my-4">Ajouter un article</a>
    <?php if($articles): ?>
      <?php foreach ($articles as $article): ?>
        <?php require 'include/article-teaser.php';?>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>

</body>

</html>
