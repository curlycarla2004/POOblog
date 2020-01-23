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
  protected $auteur = '';
  protected $tags = [];

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
    $this->auteur = new Auteur();
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

  public function getAuteur():Auteur {
    return $this->auteur;
  }

  public function getTags():array {
    return $this->tags;
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
    $query = 'SELECT id, titre, corps, auteur_id FROM article WHERE article.id = :article_id';
    $req = $dbh->prepare($query);
    $params = [
      'article_id' => $id
    ];
    //Éxecution de la requête.
    $req->execute($params);

    $data = $req->fetch(\PDO::FETCH_ASSOC);
    if (!empty($data)) {
      $article = new Article($data);
      if(!empty($data['auteur_id'])){
        $article->auteur = Auteur::charger($data['auteur_id']);
      }
      return $article;
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

    $query = 'SELECT id, titre, corps, auteur_id FROM article
    LIMIT :debut, :fin';

    $req = $dbh->prepare($query);
    $req->bindParam(':debut', $debut, \PDO::PARAM_INT);
    $req->bindParam(':fin', $limit, \PDO::PARAM_INT);
    $req->execute();
    $results = $req->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($results as $value) {
      $article = new Article($value);
      if (!empty($value['auteur_id'])) {
        $auteur = Auteur::charger($value['auteur_id']);
      }
      $article->auteur = $auteur;
      $article->tags = Tag::chargerPourArticle($value['id']);
      $articles[] = $article;
    }
    return $articles;
  }

  /**
   * Retour html pour un rendu des différents tags d'un article.
   *
   * @return string
   */
  public function afficherTags():string
  {
    $output = '';
    if($this->getTags()){
      $output = '<span class="badge badge-warning">';
      foreach ($this->getTags() as $tag) {
        $output .= $tag->getNom().' ';
      }
      $output .= '</span>';
    }
    return $output;

  }
}
