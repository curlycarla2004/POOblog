<?php

//On déclare le namesapce de notre classe.
namespace Wf3;

//On list les classes au nous allons utiliser.
use Wf3\DB;

class Auteur{

  protected $id = '';
  protected $nom = '';
  protected $prenom = '';

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

  public function getPrenom(): string
  {
    return $this->prenom;
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
      $query = 'UPDATE auteur
      SET nom = :nom, prenom = :prenom
      WHERE id = :id';
      $params = [
        ':id' => $this->id,
        ':nom' => $this->nom,
        ':prenom' => $this->prenom,
      ];
    }
    //sinon, il s'agit d'un INSERT.
    else {
      $query = 'INSERT INTO auteur (nom, prenom)
      VALUES (:nom, :prenom)';
      $params = [
        ':nom' => $this->nom,
        ':prenom' => $this->prenom,
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
   * @return Auteur ou NULL
   */
  static public function charger(int $id)
  {
    //Création d'une instance PDO.
    $dbh = DB::connect();
    $query = 'SELECT * FROM auteur WHERE auteur.id = :id';
    $req = $dbh->prepare($query);
    $params = [
      ':id' => $id
    ];
    //Éxecution de la requête.
    $req->execute($params);

    $data = $req->fetch(\PDO::FETCH_ASSOC);
    if (!empty($data)) {
      return new Auteur($data);
    } else
      return NULL;
  }

  /**
   * Méthode statique de chargement de plusieurs auteurs.
   *
   * @return Auteur[]
   */
  static public function chargerPlusieurs(int $debut = 0, int $limit = 10): array
  {
    $auteurs = [];
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
      $auteurs[] = new Auteur($value);
    }
    return $auteurs;
  }

}
