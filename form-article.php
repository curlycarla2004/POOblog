<?php

//Il faut toujours inclure l'autoload.
require 'vendor/autoload.php';

//Nous listons ici les classes dont nous avons besoin dans index.php.
use Wf3\Article;

$article_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
//Si l'id de l'article est passé dans l'url alors, il s'agit d'une modif.
if ($article_id)
  $article = Article::charger($article_id);
//Sinon c'est un nouvel article.
  else
  $article = new Article();
?>

<!DOCTYPE html>
<html lang="en">

<?php
$titre_page = "Formulaire article";
require_once('include/head.php');
?>

<body>

  <div class="container py-5 mb-5">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Liste</a></li>
        <li class="breadcrumb-item active" aria-current="page">Formulaire d'édition</li>
      </ol>
    </nav>

    <!-- Formulaire de création de l'article. -->
    <form action="form-article-submit.php" method="post" enctype="multipart/form-data" class="shadow p-5 my-5 rounded border w-50 mx-auto">
      <div class="form-group">
        <label for="titre">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo $article->getTitre(); ?>">
        <small class="form-text text-muted">Titre de l'article</small>
      </div>
      <div class="form-group">
        <label for="corps">Contenu</label>
        <textarea class="form-control" id="corps" name="corps" rows="3"><?php echo $article->getCorps(); ?></textarea>
        <small class="form-text text-muted">Contenu de l'article</small>
      </div>
      <input type="hidden" name="id" value="<?php echo $article->getId();?>">

      <button type="submit" class="btn btn-primary">Créer</button>
    </form>
  </div>
</body>

</html>
