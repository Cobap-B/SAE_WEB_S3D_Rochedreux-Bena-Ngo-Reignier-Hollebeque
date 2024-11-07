<?php
namespace NRV\User;

class User {

    private int $role;
    private string $mail;
    private int $int;
    private array $favorites;

    public function __construct(int $id, int $role, string $mail){
        $this->role = $role;
        $this->mail = $mail;
        $this->id = $id;
        $this->favorites = new \NRV\List\Favorite($id);
    }

    public function  __get(string $at):mixed{
        if (property_exists ($this, $at)) return $this->$at;
        throw new \NRV\Exception\InvalidPropertyNameException ("$at: invalid proprety");
    }

   
}