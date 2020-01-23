<?php

//On déclare le namesapce de notre classe.
namespace Wf3;

//On list les classes au nous allons utiliser.
use Wf3\DB;

class Article
{
  protected $id = '';
  protected $titre = '';
  protected $corps = '';

  /**
   * COnstructeur de la classe Article.
   *
   * @params array la liste des champs d'un article
   */
  public function __construct(array $params = [])
  {
    if (!empty($params)) {
      $this->hydrater($params);
    }
  }

  public function getId():string{
    return $this->id;
  }

  public function getTitre():string{
    return $this->titre;
  }

  public function getCorps():string{
    return $this->corps;
  }

  public function hydrater(array $data)
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

  /**
   * Enregistrement d'un article en base de données.
   */
  public function enregistrer()
  {
    $dbh = DB::connect();
    //Si l'id est déjà setté alors il s'agit d'une mise à jour.
    if($this->id){
      $query = 'UPDATE article
      SET titre = :titre, corps = :corps
      WHERE id = :id';
      $params = [
        ':id' => $this->id,
        ':titre' => $this->titre,
        ':corps' => $this->corps,
      ];
    }
    //sinon, il s'agit d'un INSERT.
    else{
      $query = 'INSERT INTO article (titre, corps)
      VALUES (:titre, :corps)';
      $params = [
        ':titre' => $this->titre,
        ':corps' => $this->corps,
      ];
    }
    $req = $dbh->prepare($query);
    return $req->execute($params);
  }

  /**
   * Méthode statique de chargement d'un article précis.
   * Cette méthode peut être appellée sans que la classe Article ne soit
   * instanciée. ex: $mon_article = Article::charger(4);
   *
   * @return Article ou NULL
   */
  static public function charger(int $id)
  {
    //Création d'une instance PDO.
    $dbh = DB::connect();
    $query = 'SELECT id, titre, corps FROM article WHERE article.id = :article_id';
    $req = $dbh->prepare($query);
    $params = [
      'article_id' => $id
    ];
    //Éxecution de la requête.
    $req->execute($params);

    $data = $req->fetch(\PDO::FETCH_ASSOC);
    if (!empty($data)) {
      return new Article($data);
    } else
      return NULL;
  }

  /**
   * Méthode statique de chargement de plusieurs articles.
   *
   * @return Article[]
   */
  static public function chargerPlusieurs(int $debut = 0, int $limit = 10):array
  {
    $articles = [];
    //Création d'une instance PDO.
    $dbh = DB::connect();

    $query = 'SELECT id, titre, corps FROM article
    LIMIT :debut, :fin';

    $req = $dbh->prepare($query);
    $req->bindParam(':debut', $debut, \PDO::PARAM_INT);
    $req->bindParam(':fin', $limit, \PDO::PARAM_INT);
    $req->execute();
    $results = $req->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($results as $value) {
      $articles[] = new Article($value);
    }
    return $articles;
  }
}
