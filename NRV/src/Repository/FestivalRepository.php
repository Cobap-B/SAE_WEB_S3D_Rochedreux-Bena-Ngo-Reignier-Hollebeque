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

    public function saveParty(Party $p, int $idfestival): Party{
        $stmt = $this->bd->prepare("INSERT INTO Party (idParty, nomParty, dateDebut, dateFin, lieu) VALUES (?, ?, ?, ?, ?)");
        $idP = $p->idParty;
        $nP = $p->nomParty;
        $dD = $p->dateDebut;
        $dF = $p->dateFin;
        $l = $p->lieu;
        $stmt->bindParam(1, $idP);
        $stmt->bindParam(2, $nP);
        $stmt->bindParam(3, $dD);
        $stmt->bindParam(4, $dF);
        $stmt->bindParam(5, $l);
        $stmt->execute();

        $lastInsertId = (int)$this->bd->lastInsertId();

        $stmt2 = $this->bd->prepare("INSERT INTO Festival2Party (idFestival, idParty) VALUES (?,?)");
        $stmt2->execute([$idfestival, $lastInsertId]);
        return $p;
    }

    public function saveShow(Show $spec, int $idparty): int{
        $query = "INSERT into Shows (categorie, title, artist, dateDebut, dateFin) VALUES (?, ?, ?, ?, ?)";
        $prep = $this->bd->prepare($query);
        $cat = $spec->category;
        $tit = $spec->title;
        $art = $spec->artist;
        $dated = $spec->dateDebut;
        $datef = $spec->dateFin;
        $prep->bindParam(1,$cat);
        $prep->bindParam(2,$tit);
        $prep->bindParam(3,$art);
        $prep->bindParam(4,$dated);
        $prep->bindParam(5,$datef);
        $prep->execute();

        $query = "INSERT into Party2Show (idParty, idShow) VALUES (?, ?)";
        $prep = $bd->prepare($query);
        $prep->bindParam(1,$idparty);
        $lastid = $bd->lastInsertId();
        $prep->bindParam(2, $lastid);
        $prep->execute();

        return $lastid;
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
        $query = "SELECT shows.idshow from shows 
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
        $html = "";

        while ($row = $prep->fetch(PDO::FETCH_ASSOC)) {
            $html .= $row['idshow'];
            $html .= '<br>';
        }

        return $html;
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
}