<?php
namespace NRV\Auth;
use NRV\Repository\FestivalRepository as FestivalRepository;
use PDO;

abstract class AuthnProvider {

    public static function authenticate(string $e, string $p){
        try {
        $bd = FestivalRepository::makeConnection();
        // $query = "select email, pwd, role from UserNRV where email = ? ";
        // $prep = $bd->prepare($query);
        // $prep->bindParam(1,$e);
        // $bool = $prep->execute();
        // $data =$prep->fetch(PDO::FETCH_ASSOC);
        $data = $bd->getPwdRole($e);
        if (isset($data['email'])){
            $hash=$data['pwd'] ;
            if (!password_verify($p, $hash)) throw new \Exception();
            // $query = "select idUser from UserNRV where email = ? ";
            // $prep = $bd->prepare($query);
            // $prep->bindParam(1,$e);
            // $prep->execute();
            // $ide = $prep->fetch(PDO::FETCH_ASSOC)['idUser'];
            $ide = $bd->getIdUser($e);
            $_SESSION['user']['id']=$ide;
            $_SESSION['user']['email']=$e;
            $_SESSION['user']['role']=$data['role'];

            $favorite = $bd->getFavorite($ide);
            $_SESSION["Favorite"] = [];
            foreach($favorite as $f){
                array_push($_SESSION["Favorite"], $f);
            }
            return "Vous êtes connecté";
            }
        else {
            return "Aucun compte n'est associé à cette adresse email";
            }
        }
        catch (\Exception){
            return "Mot de passe Invalide";
        }
    }

    public static function register(string $e, string $p, int $role){
        $res = "Echec de l'inscription";
        $min = 10;

        $bd = FestivalRepository::makeConnection();
        // $query = "select pwd from UserNRV where email = ? ";
        // $prep = $bd->prepare($query);
        // $prep->bindParam(1,$e);
        // $prep->execute();
        // $d = $prep->fetchall(PDO::FETCH_ASSOC);
        $d = $bd->getPwd($e);
        // $d sert a voir si l'user est deja inscrit
        if((strlen($p) >= $min)
        &&(sizeof($d)==0)
        && preg_match("#[\d]#", $p)
        && preg_match("#[\W]#", $p)
        && preg_match("#[\a-z]#", $p)
        && preg_match('/[A-Z]/', $p)){

            $hash = password_hash($p, PASSWORD_DEFAULT,['cost'=>10]);
            
            // $insert = "INSERT into UserNRV (email, pwd, role) values(?,?,?)";
            // $prep = $bd->prepare($insert);
            // $prep->bindParam(1,$e);
            // $prep->bindParam(2,$hash);
            // $prep->bindParam(3,$role);
            // $bool = $prep->execute();
            $bool = $bd->insertUser($e, $hash, $role);
            if($bool){
                $res = "Inscription réalisée avec succès";
            }
        }
        elseif (sizeof($d)!==0){
            $res = "Cette adresse email est déjà associée à un compte.";
        }
        elseif (!preg_match("#[\d]#", $p) || !preg_match("#[\W]#", $p) || !preg_match("#[\a-z]#", $p) || !preg_match('/[A-Z]/', $p)){
            $res = "Veuillez utiliser au moins 10 caractères, avec au moins une majuscule, un chiffre et un caractère spécial";
        }
        return $res;
    }
}
