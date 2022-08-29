<?php

namespace ex14\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    public function getTable(){
        $table = [];
        try{
            $sql = "SELECT * from person_e14";
            $em = $this->get('doctrine.orm.default_entity_manager');
            $statement = $em->getConnection()->prepare($sql);
            $statement->execute();
        }
        catch(\Exception $e){
            echo  "Error";
            return $table;
        }
        $table = [];
        foreach($statement->fetchAll() as $t){
            array_push($table, [
                'id' => $t["id"],
                'username' => $t["username"],
            ]);
        }
        return $table;
    }
    /**
     * @Route("/e14/")
     */
    public function indexAction(Request $request)
    {
        $error = "Table existe";
        try{
            $sql = "SELECT * from person_e14";
            $em = $this->get('doctrine.orm.default_entity_manager');
            $statement = $em->getConnection()->prepare($sql);
            $statement->execute();
        }
        catch(\Exception $e){
            $error = "Table n'existe pas";
            return $this->render('ex14:Default:index.html.twig',
            [
                "table" => "",
                "error" => $error,
            ]);
        }
        return $this->render('ex14:Default:index.html.twig',
        [
            "table" => $this->getTable(),
            "error" => $error,
        ]);
    }

    /**
     * @Route("/e14/form/")
     */
    public function formPage(Request $request)
    {
        $username = $request->query->get('_username');
        $sql = "INSERT INTO person_e14 (username)
        VALUES ('". $username . "');";
        $em = $this->get('doctrine.orm.default_entity_manager');
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        return $this->render('ex14:Default:index.html.twig',
        [
            "table" => $this->getTable(),
            "error" => "",
        ]);
    }

    /**
     * @Route("/e14/create/")
     */
    public function createTable(){
        $sql = "CREATE TABLE IF NOT EXISTS person_e14("
                . "id int NOT NULL AUTO_INCREMENT,"
                . "username varchar(255) UNIQUE,"
                . "PRIMARY KEY (id)"
                . ");";
        $em = $this->get('doctrine.orm.default_entity_manager');
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        return $this->render('ex14:Default:index.html.twig',
        [
            "table" => $this->getTable(),
            "error" => "Table existe",
        ]);
    }
}
