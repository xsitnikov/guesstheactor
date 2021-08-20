<?php


namespace App\Interfaces;


interface ExternalApiInterface
{
    public function getPopularActors();

    public function checkName($id,$name);
}
