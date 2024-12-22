<?php
class Utilisateur
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  // Insérer un utilisateur
  public function insert($nom_utilisateur, $nom, $pNom, $mdp, $mail, $id_role, $id_applis){
    try {
      // Début de la transaction
      $this->db->beginTransaction();

      // Insertion dans la table utilisateur
      $queryUtilisateur = $this->db->prepare("
                INSERT INTO utilisateur (nom_utilisateur, nom, pNom, mdp, mail)
                VALUES (?, ?, ?, ?, ?)
            ");
      $queryUtilisateur->execute([$nom_utilisateur, $nom, $pNom, $mdp, $mail]);
      $id_utilisateur = $this->db->lastInsertId();

      // Insertion dans la table utilisateur_role
      $queryRole = $this->db->prepare("
                INSERT INTO utilisateur_role (id_utilisateur, id_rôle)
                VALUES (?, ?)
            ");
      $queryRole->execute([$id_utilisateur, $id_role]);

      // Insertion dans la table utilisateur_applis pour chaque application sélectionnée
      $queryAppli = $this->db->prepare("
                INSERT INTO utilisateur_applis (id_utilisateur, id_appli)
                VALUES (?, ?)
            ");
      foreach ($id_applis as $id_appli) {
        $queryAppli->execute([$id_utilisateur, $id_appli]);
      }

      // Commit de la transaction
      $this->db->commit();
      return true;
    } catch (Exception $e) {
      // Annulation en cas d'erreur
      $this->db->rollBack();
      throw $e;
    }
  }



  // Récupérer tous les utilisateurs avec leurs rôles et applications
  public function getAllUsers()
  {
    $query = "
        SELECT 
            u.id_utilisateur,
            u.nom_utilisateur,
            u.nom,
            u.pNom,
            u.mail,
            r.libelle_rôle,
            r.id_rôle,
            a.libelle_appli,
            a.id_appli,
            GROUP_CONCAT(a.libelle_appli SEPARATOR ', ') AS applications
        FROM utilisateur u
        JOIN utilisateur_role ur ON u.id_utilisateur = ur.id_utilisateur
        JOIN role r ON ur.id_rôle = r.id_rôle
        JOIN utilisateur_applis ua ON u.id_utilisateur = ua.id_utilisateur
        JOIN applis a ON ua.id_appli = a.id_appli
        GROUP BY 
            u.id_utilisateur, 
            u.nom_utilisateur, 
            u.nom, 
            u.pNom, 
            u.mail, 
            r.libelle_rôle
    ";

    // Ici j'ai ajoute PDO::FETCH_ASSOC pour ne recuperer que les colones est non les indices associe a celle-ci 

    return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
  }


  // Supprimer un utilisateur par son ID
  public function delete($id_utilisateur)
  {
    try {
      $this->db->beginTransaction();

      // Suppression des relations dans utilisateur_role
      $this->db->prepare("DELETE FROM utilisateur_role WHERE id_utilisateur = ?")->execute([$id_utilisateur]);

      // Suppression des relations dans utilisateur_applis
      $this->db->prepare("DELETE FROM utilisateur_applis WHERE id_utilisateur = ?")->execute([$id_utilisateur]);

      // Suppression dans la table utilisateur
      $this->db->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?")->execute([$id_utilisateur]);

      $this->db->commit();
      return true;
    } catch (Exception $e) {
      $this->db->rollBack();
      throw $e;
    }
  }

  public function getUserById($id_utilisateur) {
    $query = "
        SELECT u.id_utilisateur, u.nom_utilisateur, u.nom, u.pNom, u.mail
        FROM utilisateur u
        WHERE u.id_utilisateur = ?
    ";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$id_utilisateur]);
    return $stmt->fetch();
}

public function getApplicationsByUserId($id_utilisateur) {
    $query = "
        SELECT a.id_appli, a.libelle_appli
        FROM applis a
        JOIN utilisateur_applis ua ON a.id_appli = ua.id_appli
        WHERE ua.id_utilisateur = ?
    ";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$id_utilisateur]);
    return $stmt->fetchAll();
}

  public function deleteApplicationForUser($id_utilisateur, $id_appli) {
      $query = "DELETE FROM utilisateur_applis WHERE id_utilisateur = ? AND id_appli = ?";
      $stmt = $this->db->prepare($query);
      $stmt->execute([$id_utilisateur, $id_appli]);
  }

  public function countApplicationsForUser($id_utilisateur)
  {
      $query = "
          SELECT COUNT(*) AS application_count
          FROM utilisateur_applis
          WHERE id_utilisateur = ?
      ";
      $stmt = $this->db->prepare($query);
      $stmt->execute([$id_utilisateur]);
      $result = $stmt->fetch();
      return $result['application_count'];
  }

}

?>

