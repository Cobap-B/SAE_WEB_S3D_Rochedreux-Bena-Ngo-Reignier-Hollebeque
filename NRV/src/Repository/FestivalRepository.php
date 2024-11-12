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
        return self::$instance;
    }

    public function findPartyById(int $id): ?Party{
        $stmt = self::$instance->bd->prepare('SELECT idParty, nomParty FROM Party WHERE idParty = ?');
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        if($data){
            $party = new Party($data['nom']);
        }

        return $party;
    }

    public function saveParty(string $name, string $dateD, string $dateF, string $hourStart, string $hourEnd, string $idLoc, int $price ): \NRV\Event\Party{
        $stmt = $this->bd->prepare("INSERT INTO party (partyName, dateStart, dateEnd, idLocation, pricing) 
        VALUES (:n, STR_TO_DATE( :d1 :h1,'%Y-%m-%d %H:%i'), STR_TO_DATE( :d2 :h2,'%Y-%m-%d %H:%i'), :i, :p)");
        $stmt->bindParam(":n", $name);
        $stmt->bindParam(":d1", $dateD);
        $stmt->bindParam(":h1", $hourStart);
        $stmt->bindParam(":d2", $dateF);
        $stmt->bindParam(":h2", $hourEnd);
        $stmt->bindParam(":i", $idLoc);
        $stmt->bindParam(":p", $price);
        $stmt->execute();

        $lastInsertId = (int)$this->bd->lastInsertId();

        $dhS = $dateD . " " . $hourStart . ":00";
        $dhF = $dateF . " " . $hourEnd . ":00";

        $place = $this->getPlace($idLoc);

        $party = new \NRV\Event\Party($lastInsertId, $name, $dhS, $dhF, $place, $price);
        
        return $party;
    }

    public function saveShow(string $categorie, string $title, string $artist, string $dateD, string $dateF, string $hourStart, string $hourEnd, string $desc, string $picture, string $audio): \NRV\Event\Show{
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

    public function delShow(Show $spec){
        $query = "DELETE from Party2Show where idShow = ?";
        $prep = $this->bd->prepare($query);
        $id = $spec->id;
        $prep->bindParam(1,$id);
        $prep->execute();

        $query = "DELETE from Shows where idShow = ?";
        $prep = $bd->prepare($query);
        $prep->bindParam(1,$id);
        $prep->execute();
    }

    public function displayShow(string $category, string $date, string $lieu){
        $query = "SELECT shows.idshow, shows.categorie, shows.title, shows.artist, shows.dateStart, shows.dateEnd, shows.imageName, shows.audioName from shows 
            INNER JOIN party2show on shows.idshow = party2show.idShow
            INNER JOIN party on party2show.idParty = party.idParty WHERE";
        if ($category != ""){
            $query.=" categorie = :category AND";
        }if ($date != ""){
            $query.=" DATE(party.dateStart)=STR_TO_DATE(:dateVar,'%Y-%m-%d') AND";
        }if ($lieu != ""){
            $query.=" party.location = :lieu";
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
            $prep->bindParam(':lieu',$lieu, PDO::PARAM_STR);
        }
        
        $prep->execute();
        $shows = [];

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
           $show = new \NRV\Event\Show($row['idshow'], $row['categorie'], $row['title'], $row['dateStart'], $row['dateEnd'], $row['artist'], "",  $row['audioName'], $row['imageName']);
           array_push($shows, $show);
        }

        return $shows;
    }


    public function displayParty(){
        $query = "SELECT * from party";

        $prep = $this->bd->prepare($query);
        $prep->execute();
        $html = "";

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            $html .= $row['idParty'];
            $html .= '<br>';
        }

        return $html;
    }

    public function displayFavorite(string $id){
        $query = "SELECT idUser, idShow FROM favorite WHERE id = :id";
        $prep = $this->bd->prepare($query);
        $prep->bindParam(':id', $id);
        $prep->execute();
        $html = "";

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            $html .= $row['idshow'];
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
}