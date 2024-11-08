<?php
namespace NRV\Repository;
use PDO;

class FestivalRepository{
    private static array $tab = [];
    public static ?PDO $bd = null;

    public static function setConfig(String $file ){
        self::$tab = parse_ini_file($file);
    }

    public static function makeConnection(){
        if(is_null(self::$bd)){
            $res = self::$tab['driver'].":host=".self::$tab['host'].";dbname=".self::$tab['database'];
            self::$bd = new PDO($res, self::$tab['username'], self::$tab['password']);
        }
        return self::$bd ;
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

    public static function saveShow(Show $spec, int $idparty): int{
        $bd = FestivalRepository::makeConnection();

        $query = "INSERT into Shows (categorie, title, artist, dateDebut, dateFin) VALUES (?, ?, ?, ?, ?)";
        $prep = $bd->prepare($query);
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

    public static function delShow(Show $spec){
        $bd = FestivalRepository::makeConnection();
        $query = "DELETE from Party2Show where idShow = ?";
        $prep = $bd->prepare($query);
        $id = $spec->id;
        $prep->bindParam(1,$id);
        $prep->execute();

        $query = "DELETE from Shows where idShow = ?";
        $prep = $bd->prepare($query);
        $prep->bindParam(1,$id);
        $prep->execute();
    }
}