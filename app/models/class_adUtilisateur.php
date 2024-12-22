<?php
class adUtilisateur {
    
    private $uploadDir;
    private $uploadFile;
    private $file;
    private $bddListe;
    public function __construct($file, $bddListe){
        $this->uploadDir = '../app/uploads/';
        $this->file = $file;
        $this->uploadFile = $this->uploadDir . basename($this->file['name']);
        $this->bddListe = $bddListe;
    }

    public function convertCsv(){

        move_uploaded_file($this->file['tmp_name'], $this->uploadFile);

        $inputCsvFile = explode("\n", file_get_contents('../app/uploads/testFile.csv'));
        $finalListe = [];
        $finalListe1 = [];
        $finalListe2 = $this->bddListe;
        $returnList = [];

        foreach ($inputCsvFile as $key) {
            $param = explode(',', $key);
            $finalListe[] = ['id_utilisateur'=>$param[0], 'nom_utilisateur'=>$param[1], 'nom'=>$param[2], 'pNom'=>$param[3], 'mail'=>$param[4], 'libelle_rôle'=>$param[5],'id_rôle'=>$param[6], 'libelle_appli'=>$param[7],'id_appli'=>$param[8]];
        }
    
        foreach ($finalListe as $utilisateur) {
            if (!(in_array($utilisateur, $this->bddListe))) {
                $finalListe1[] = $utilisateur;
            }
        }

        $returnList[] = $finalListe1;
        $returnList[] = $finalListe2;
        
        return $returnList;
    }

}
