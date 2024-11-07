<?php
namespace NRV\List;

class FavoriteList{

    private int $id;
    private array $shows;

    public function __construct(string $id, array $shows = []){
        $this->id = $id;    
        $this->shows = $shows;
    } 

    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new \NRV\Exception\InvalidPropertyNameException ("$at: invalid proprety");
    }

    public function addFavorite(\NRV\Event\Show $show){
        array_push($this->favorites, $show);
    }

    public function delFavorite(int $i){
        if (isset($this->favorites[$i])) unset($this->favorites[$i]);
        else throw new \NRV\Exception\InvalidFavoriteException ("$i: not a show ?");
        
    }
}