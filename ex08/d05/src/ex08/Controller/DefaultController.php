<?php

namespace ex08\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
     /**
     * @Route("/e08/")
     */
    public function putButton()
    {

        return $this->render('ex08:Default:index.html.twig',[
            "error" => "",
            "buttons" => false,
            ]
        );
    }

    /**
     * @Route("/e08/create/")
     */
    public function indexAction()
    {
        $sql = "CREATE TABLE persons("
            . "id int NOT NULL AUTO_INCREMENT,"
            . "username varchar(255) UNIQUE,"
            . "name varchar(255),"
            . "email varchar(255) UNIQUE,"
            . "enable boolean,"
            . "birthdate datetime,"
            . "PRIMARY KEY (id)"
            . ");";
        $error = "Success: the table is created succefully";
        $em = $this->get('doctrine.orm.default_entity_manager');
        try {
            $statement = $em->getConnection()->prepare($sql);
            $statement->execute();
        }
            catch(\Exception $e){
                $error = "Error: the table already exist";
        }
        return $this->render('ex08:Default:index.html.twig',[
            "error" => $error,
            "buttons" => true,
            ]
        );
    }
    /**
     * @Route("/e08/create/tables")
     */
     public function createTables()
    {
        $sql = "CREATE TABLE bank_account("
            . "account_id INT PRIMARY KEY,"
            . "amount int,"
            . "person_id int UNIQUE NOT NULL,"
            . "CONSTRAINT person_id FOREIGN KEY (person_id) REFERENCES persons (id)"
            . ");";
        $error = "Success: the table is created succefully";
        $em = $this->get('doctrine.orm.default_entity_manager');
        try {
            $statement = $em->getConnection()->prepare($sql);
            $statement->execute();
        }
            catch(\Exception $e){
                var_dump($e);
        }
        $sql = "CREATE TABLE addresses("
            . "name varchar(255),"
            . "person_id int NOT NULL,"
            . "CONSTRAINT person_id FOREIGN KEY (person_id) REFERENCES persons (id)"
            . ");";
        $em = $this->get('doctrine.orm.default_entity_manager');
        try {
            $statement = $em->getConnection()->prepare($sql);
            $statement->execute();
        }
            catch(\Exception $e){
                $error = $e;
        }
        return $this->render('ex08:Default:index.html.twig',[
            "error" => $error,
            "buttons" => true,
            ]
        );
    }
}
