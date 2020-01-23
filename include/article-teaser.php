<article class="shadow rounded border p-4 mb-5">
  <div class="row">
    <div class="col-3">
      <img src="img/default/no-picture.png" class="img-fluid" alt="" srcset="">
    </div>
    <div class="col-9">
      <h2 class="text-primary text-uppercase"><?php echo $article->getTitre(); ?></h2>
      <p class="text-justify">
        <?php
        $teaser = strlen($article->getCorps()) > 320 ? substr($article->getCorps(), 0, 320) . "..." : $article->getCorps();
        echo $teaser;
        ?>
      </p>
      <hr>
    </div>

  </div>
  <footer class="d-flex justify-content-between align-items-center">
    <div class="d-flex pt-2">
      <a href="form-article.php?id=<?php echo $article->getId(); ?>" class="btn btn-outline-primary"><i class="fa fa-pencil pr-2" aria-hidden="true"></i>Éditer</a>
    </div>
    <div class="d-flex">
      <h6 class="text-muted pr-4"><i class="fa fa-user pr-2" aria-hidden="true"></i><?php echo $article->getAuteur()->afficher(); ?></h6>
      <h6 class="text-muted"><i class="fa fa-calendar pr-2" aria-hidden="true"></i>TODO</h6>
    </div>
  </footer>
</article>
