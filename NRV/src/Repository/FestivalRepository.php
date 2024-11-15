<?php
namespace NRV\Repository;
use PDO;

class FestivalRepository{
    private static array $tab = [];
    public ?PDO $bd = null;
    private static ?FestivalRepository $instance = null;

    private function __construct(array $conf) {
        $res = self::$tab['driver'].":host=".self::$tab['host'].";dbname=".self::$tab['database'];
        $this->bd = new PDO($res, $conf['username'], $conf['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    }

    public static function setConfig(String $file ){
        self::$tab = parse_ini_file($file);
    }
    public static function makeConnection(){
        if (is_null(self::$instance)) {
            self::$instance = new FestivalRepository(self::$tab);
        }
        self::$instance->bd->exec("SET NAMES 'utf8mb4'");
        return self::$instance;
    }


    public function saveParty(string $name, string $dateD, string $dateF, string $hourStart, string $hourEnd, string $idLoc, int $price, string $video ): \NRV\Event\Party{
        $stmt = $this->bd->prepare("INSERT INTO party (partyName, dateStart, dateEnd, idLocation, pricing, link) 
        VALUES (:n, STR_TO_DATE( :d1 :h1,'%Y-%m-%d %H:%i'), STR_TO_DATE( :d2 :h2,'%Y-%m-%d %H:%i'), :i, :p, :v)");
        $stmt->bindParam(":n", $name, PDO::PARAM_STR);
        $stmt->bindParam(":d1", $dateD);
        $stmt->bindParam(":h1", $hourStart);
        $stmt->bindParam(":d2", $dateF);
        $stmt->bindParam(":h2", $hourEnd);
        $stmt->bindParam(":i", $idLoc);
        $stmt->bindParam(":p", $price);
        $stmt->bindParam(":v", $video);
        $stmt->execute();

        $lastInsertId = (int)$this->bd->lastInsertId();

        $dhS = $dateD . " " . $hourStart . ":00";
        $dhF = $dateF . " " . $hourEnd . ":00";

        $place = $this->getPlace($idLoc);

        $party = new \NRV\Event\Party($lastInsertId, $name, $dhS, $dhF, $place, $price, $video);
        
        return $party;
    }
    
    public function savePartyWithNewLoc(string $name, string $dateD, string $dateF, string $hourStart, string $hourEnd, int $price, string $video, string $locName, string $address, int $nbPlAs, int $nbPlDe, string $imgLoc): \NRV\Event\Party{
        $stmt = $this->bd->prepare("INSERT INTO location(locaName, address, nbPlacesAss, nbPlacesDeb, imagePath)
        VALUES (:locN, :adr, :PlA, :PlD, :img)");
        $stmt->bindParam(":locN", $locName, PDO::PARAM_STR);
        $stmt->bindParam(":adr", $address, PDO::PARAM_STR);
        $stmt->bindParam(":PlA", $nbPlAs);
        $stmt->bindParam(":PlD", $nbPlDe);
        $stmt->bindParam(":img", $imgLoc);
        $stmt->execute();

        $idLoc = (int)$this->bd->lastInsertId();
        
        $stmt = $this->bd->prepare("INSERT INTO party (partyName, dateStart, dateEnd, idLocation, pricing, link) 
        VALUES (:n, STR_TO_DATE( :d1 :h1,'%Y-%m-%d %H:%i'), STR_TO_DATE( :d2 :h2,'%Y-%m-%d %H:%i'), :i, :p, :v)");
        $stmt->bindParam(":n", $name, PDO::PARAM_STR);
        $stmt->bindParam(":d1", $dateD);
        $stmt->bindParam(":h1", $hourStart);
        $stmt->bindParam(":d2", $dateF);
        $stmt->bindParam(":h2", $hourEnd);
        $stmt->bindParam(":i", $idLoc);
        $stmt->bindParam(":p", $price);
        $stmt->bindParam(":v", $video);
        $stmt->execute();

        $lastInsertId = (int)$this->bd->lastInsertId();

        $dhS = $dateD . " " . $hourStart . ":00";
        $dhF = $dateF . " " . $hourEnd . ":00";

        $place = $this->getPlace($idLoc);

        $party = new \NRV\Event\Party($lastInsertId, $name, $dhS, $dhF, $place, $price, $video);
        
        return $party;
    }


    public function saveShow(string $categorie, string $title, string $artist, string $dateD, string $dateF, string $hourStart, string $hourEnd, string $desc, string $audio, string $picture): \NRV\Event\Show{
        $query = "INSERT into shows (categorie, title, artist, dateStart, dateEnd, imageName, audioName) 
            VALUES (:c, :t, :a,  STR_TO_DATE( :d1 :h1,'%Y-%m-%d %H:%i'), STR_TO_DATE( :d2 :h2,'%Y-%m-%d %H:%i'), :p, :audio)";
        $prep = $this->bd->prepare($query); 
        $prep->bindParam(":c",$categorie, PDO::PARAM_STR);
        $prep->bindParam(":t",$title, PDO::PARAM_STR);
        $prep->bindParam(":a",$artist, PDO::PARAM_STR);
        $prep->bindParam(":d1",$dateD);
        $prep->bindParam(":h1",$hourStart);
        $prep->bindParam(":d2",$dateF);
        $prep->bindParam(":h2",$hourEnd);
        $prep->bindParam(":p",$picture);
        $prep->bindParam(":audio",$audio);
        $prep->execute();

        $lastId = $this->bd->lastInsertId();

        $dhS = $dateD . " " . $hourStart . ":00";

        $dhF = $dateF . " " . $hourEnd . ":00";

        $show = new \NRV\Event\Show($lastId, $categorie, $title, $dhS, $dhF, $artist, $desc, $audio, $picture);

        return $show;
    }
//
//    public function delShow(Show $spec){
//        $query = "DELETE from Party2Show where idShow = ?";
//        $prep = $this->bd->prepare($query);
//        $id = $spec->id;
//        $prep->bindParam(1,$id);
//        $prep->execute();
//
//        $query = "DELETE from Shows where idShow = ?";
//        $prep = $bd->prepare($query);
//        $prep->bindParam(1,$id);
//        $prep->execute();
//    }

    public function displayShow(string $category, string $date, string $lieu){
        $query = "SELECT DISTINCT (shows.idshow), shows.categorie, shows.description, shows.title, shows.artist, shows.dateStart, shows.dateEnd, shows.imageName, shows.audioName from shows 
            INNER JOIN party2show on shows.idshow = party2show.idShow
            INNER JOIN party on party2show.idParty = party.idParty WHERE";
        if ($category != ""){
            $query.=" categorie = :category AND";
        }if ($date != ""){
            $query.=" DATE(party.dateStart)=STR_TO_DATE(:dateVar,'%Y-%m-%d') AND";
        }if ($lieu != ""){
            $query.=" party.idLocation = :lieu";
        }

        $words = explode( " ", $query );
        if ($words[count($words)-1] == "AND" || $words[count($words)-1] == "WHERE"){
            array_splice( $words, -1 );
        }
        $query = implode( " ", $words );
        $prep = $this->bd->prepare($query);

        if ($category != ""){
            $prep->bindParam(':category',$category, PDO::PARAM_STR);
        }if ($date != ""){
            $prep->bindParam(':dateVar',$date, PDO::PARAM_STR);
        }if ($lieu != ""){
            $prep->bindParam(':lieu',$lieu, PDO::PARAM_INT);
        }

        $prep->execute();

        $shows = [];

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            $show = new \NRV\Event\Show($row['idshow'], $row['categorie'], $row['title'], $row['dateStart'], $row['dateEnd'], $row['artist'], $row['description'],  $row['audioName'], $row['imageName']);
            array_push($shows, $show);
        }

        return $shows;
    }

    public function displayAllShow(){
        $query = "SELECT DISTINCT (shows.idshow), shows.categorie, shows.description, shows.title, shows.artist, shows.dateStart, shows.dateEnd, shows.imageName, shows.audioName from shows";
       
        $prep = $this->bd->prepare($query);

        $prep->execute();

        $shows = [];

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            $show = new \NRV\Event\Show($row['idshow'], $row['categorie'], $row['title'], $row['dateStart'], $row['dateEnd'], $row['artist'], $row['description'],  $row['audioName'], $row['imageName']);
            array_push($shows, $show);
        }

        return $shows;
    }


    public function displayShowByPartyId(int $id){
        $query = "SELECT shows.idshow, shows.categorie, shows.description, shows.title, shows.artist, shows.dateStart, shows.dateEnd, shows.imageName, shows.audioName from shows 
            INNER JOIN party2show on shows.idshow = party2show.idShow
            INNER JOIN party on party2show.idParty = party.idParty WHERE Party.idParty = :id";

        $prep = $this->bd->prepare($query);
        $prep->bindParam(':id',$id, PDO::PARAM_INT);
        $prep->execute();
        $shows = [];

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {

            $show = new \NRV\Event\Show($row['idshow'], $row['categorie'], $row['title'], $row['dateStart'], $row['dateEnd'], $row['artist'], $row['description'],  $row['audioName'], $row['imageName']);
            array_push($shows, $show);
        }
        return $shows;
    }



    public function displayParty(): array
    {
        $query = "
        SELECT *
        FROM party p
        INNER JOIN location l ON p.idLocation = l.idLocation
        ";

        $prep = $this->bd->prepare($query);
        $prep->execute();
        $array = [];

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            $place = new \NRV\Event\Place($row['idLocation'], $row['locaName'], $row['address'], $row['nbPlacesAss'], $row['nbPlacesDeb'],$row['imagePath']);
            $party = new \NRV\Event\Party($row['idParty'], $row['partyName'], $row['dateStart'], $row['dateEnd'], $place , $row['pricing'], $row['link'],
                 $this->displayShowByPartyId($row['idParty']));

            $array[] = $party;
        }

        return $array;
    }

    public function getParty(int $id){
        $query = "
        SELECT *
        FROM party p
        INNER JOIN location l ON p.idLocation = l.idLocation
        WHERE p.idParty = :id;
        ";

        $prep = $this->bd->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        $party=null;
        $row =$prep->fetch(PDO::FETCH_ASSOC);
        if ($row){
            $place = new \NRV\Event\Place($row['idLocation'], $row['locaName'], $row['address'], $row['nbPlacesAss'], $row['nbPlacesDeb'],$row['imagePath']);
            $party = new \NRV\Event\Party($row['idParty'], $row['partyName'], $row['dateStart'], $row['dateEnd'], $place , $row['pricing'], $row['link'],
            $this->displayShowByPartyId($row['idParty']));
        }
        
        return $party;
    }

    public function displayFavorite(string $id){
        $query = "SELECT idUser, idShow FROM favorite f WHERE f.idUser = :id";
        $prep = $this->bd->prepare($query);
        $prep->bindParam(':id', $id);
        $prep->execute();
        $html = "";

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            $html .= $row['idUser'];
            $html .= '<br>';
        }

        return $html;
    }

    function getPwdRole(String $e){
        $query = "select email, pwd, role from UserNRV where email = ? ";
        $prep = $this->bd->prepare($query);
        $prep->bindParam(1,$e);
        $bool = $prep->execute();
        $data =$prep->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    function getPwd(String $e){
        $query = "select pwd from UserNRV where email = ? ";
        $prep = $this->bd->prepare($query);
        $prep->bindParam(1,$e);
        $prep->execute();
        $d = $prep->fetchall(PDO::FETCH_ASSOC);
        return $d;
    }

    function getIdUser(String $e){
        $query = "select idUser from UserNRV where email = ? ";
        $prep = $this->bd->prepare($query);
        $prep->bindParam(1,$e);
        $prep->execute();
        $ide = $prep->fetch(PDO::FETCH_ASSOC)['idUser'];
        return $ide;
    }

    function insertUser(String $e, String $p, String $r){
        $insert = "INSERT into UserNRV (email, pwd, role) values(?,?,?)";
        $prep = $this->bd->prepare($insert);
        $prep->bindParam(1,$e);
        $prep->bindParam(2,$p);
        $prep->bindParam(3,$r);
        $bool = $prep->execute();
        return $bool;
    }

    function insertPartyToShow(String $idp, String $ids){
        try {
            $insert = "INSERT into Party2Show (idParty, idShow) values(?,?)";
            $prep = $this->bd->prepare($insert);
            $prep->bindParam(1,$idp);
            $prep->bindParam(2,$ids);
            $bool = $prep->execute();
            return "Ajouté avec succès";
        }
        catch(\PDOException $e){
            $code = $e->getCode();
            if($code == 23000){
                return "Ce spectacle est déjà associé à cette soirée";
            }
            else {
                return "Erreur de l'association";
            }
        }
        
    }

    function getPlace(int $idPlace){
        $query = "select locaName, address, nbPlacesAss, nbPlacesDeb, imagePath from Location where idLocation = ?";
        $prep = $this->bd->prepare($query);
        $prep->bindParam(1,$idPlace);
        $prep->execute();

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            $place = new \NRV\Event\Place($idPlace, $row['locaName'], $row['address'], $row['nbPlacesAss'], $row['nbPlacesDeb'], $row['imagePath']);
        }
        return $place;

    }


    function getAllLocation(){
        $query = "select idLocation, locaName, address, nbPlacesAss, nbPlacesDeb, imagePath from Location";
        $prep = $this->bd->prepare($query);
        $prep->execute();
        
        $places = [];

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            $place = new \NRV\Event\Place($row['idLocation'], $row['locaName'], $row['address'], $row['nbPlacesAss'], $row['nbPlacesDeb'], $row['imagePath']);
            array_push($places, $place);
        }

        return $places;
    }




    function getIdParty(int $id){
        $query = "
        SELECT * from Party2Show where idShow = :id;
        ";

        $prep = $this->bd->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        $idParty=null;
        $row =$prep->fetch(PDO::FETCH_ASSOC);
        if ($row){
            $idParty = $row["idParty"];
        }
        
        return $idParty;
    }


    function getCategorie(){
        $query = "
        SELECT Distinct(shows.categorie) as cat from shows;
        ";

        $prep = $this->bd->prepare($query);
        $prep->execute();
        $cat=[];
        $row =$prep->fetch(PDO::FETCH_ASSOC);
        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            array_push($cat, $row["cat"]);
        }
        
        
        return $cat;
    }

    function saveFavorite(int $id){
        $query = "INSERT into Favorite (idUser, idShow) Values (:idUser, :idShow)";
        $prep = $this->bd->prepare($query); 
        $prep->bindParam(":idUser",$_SESSION['user']['id'], PDO::PARAM_INT);
        $prep->bindParam(":idShow",$id, PDO::PARAM_INT);
        $prep->execute();
    }

    function removeFavorite(int $id){
        $query = "DELETE from Favorite where idUser = :idUser and idShow = :idShow";
        $prep = $this->bd->prepare($query); 
        $prep->bindParam(":idUser",$_SESSION['user']['id'], PDO::PARAM_INT);
        $prep->bindParam(":idShow",$id, PDO::PARAM_INT);
        $prep->execute();
    }

    function getFavorite(int $id){
        $query = "Select idShow from Favorite where idUser = :idUser";
        $prep = $this->bd->prepare($query); 
        $prep->bindParam(":idUser",$id, PDO::PARAM_INT);
        $prep->execute();

        $cat=[];
        $row =$prep->fetch(PDO::FETCH_ASSOC);
        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            array_push($cat, $row["idShow"]);
        }
        return $cat;
    }
}