<?php

//On déclare le namesapce de notre classe.
namespace Wf3;

//On list les classes au nous allons utiliser.
use Wf3\DB;

class Tag
{

  protected $id = '';
  protected $nom = '';

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

  public function getId(): string
  {
    return $this->id;
  }

  public function getNom(): string
  {
    return $this->nom;
  }

  public function hydrater(array $data)
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    }
  }

  /**
   * Enregistrement d'un auteur en base de données.
   */
  public function enregistrer()
  {
    $dbh = DB::connect();
    //Si l'id est déjà setté alors il s'agit d'une mise à jour.
    if ($this->id) {
      $query = 'UPDATE tag
      SET nom = :nom
      WHERE id = :id';
      $params = [
        ':id' => $this->id,
        ':nom' => $this->nom,
      ];
    }
    //sinon, il s'agit d'un INSERT.
    else {
      $query = 'INSERT INTO tag (nom)
      VALUES (:nom)';
      $params = [
        ':nom' => $this->nom,
      ];
    }
    $req = $dbh->prepare($query);
    return $req->execute($params);
  }

  /**
   *Enregistrer un tag
   * @return Tag ou NULL
   */
  static public function charger(int $id)
  {
    //Création d'une instance PDO.
    $dbh = DB::connect();
    $query = 'SELECT * FROM tag WHERE auteur.id = :id';
    $req = $dbh->prepare($query);
    $params = [
      ':id' => $id
    ];
    //Éxecution de la requête.
    $req->execute($params);

    $data = $req->fetch(\PDO::FETCH_ASSOC);
    if (!empty($data)) {
      return new Tag($data);
    } else
      return NULL;
  }

  /**
   * Méthode statique de chargement de plusieurs auteurs.
   *
   * @return Tag[]
   */
  static public function chargerPlusieurs(int $debut = 0, int $limit = 10): array
  {
    $tags = [];
    //Création d'une instance PDO.
    $dbh = DB::connect();


      $query = 'SELECT * FROM auteur
      LIMIT :debut, :fin';

    $req = $dbh->prepare($query);
    $req->bindParam(':debut', $debut, \PDO::PARAM_INT);
    $req->bindParam(':fin', $limit, \PDO::PARAM_INT);

    $req->execute();
    $results = $req->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($results as $value) {
      $tags[] = new Tag($value);
    }
    return $tags;
  }

  /**
   * Méthode statique qui permet de récuperer tous les tags
   * pour un article.
   *
   * @param int $article_id
   * @return array
   */
  static public function chargerPourArticle($article_id):array{
    $tags = [];
    $dbh = DB::connect();
    $query = "SELECT article_has_tag.tag_id as id, article_has_tag.article_id, tag.nom
    FROM article_has_tag
    JOIN tag ON tag.id = article_has_tag.tag_id
    WHERE article_has_tag.article_id = :article_id";

    $req = $dbh->prepare($query);
    $params = [
      ':article_id' => $article_id
    ];
    //Éxecution de la requête.
    $req->execute($params);

    $results = $req->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($results as $value) {
      $tags[] = new Tag($value);
    }
    return $tags;
  }

}

