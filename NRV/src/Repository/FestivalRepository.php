<?php
namespace NRV\Repository;
use NRV\Event\Party;
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
    public function saveParty(Party $p): Party{
        $stmt = $this->bd->prepare("INSERT INTO Party (idParty, nomParty, dateDebut, dateFin, lieu) VALUES (:id, :nom, :dateDebut, :dateFin, :lieu)");
        $stmt->bindParam(':id', $p->__get(id), PDO::PARAM_INT);
        $stmt->bindParam(':nom', $p->__get(name), PDO::PARAM_STR);
        $stmt->bindParam(':dateDebut', $p->__get(dateDebut), PDO::PARAM_STR);
        $stmt->bindParam(':dateFin', $p->__get(dateFin), PDO::PARAM_STR);
        $stmt->bindParam(':lieu', $p->__get(place), PDO::PARAM_STR);
        $stmt->execute();

        return $p;
    }
}