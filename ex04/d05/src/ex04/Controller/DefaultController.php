<?php

namespace ex04\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    public function getTable(){
        $sql = "SELECT * from person_e04";
        $em = $this->get('doctrine.orm.default_entity_manager');
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $table = [];
        foreach($statement->fetchAll() as $t){
            array_push($table, [
                'id' => $t["id"],
                'username' => $t["username"],
                'name' => $t["name"],
                'email' => $t["email"],
                'enable' => $t["enable"],
                'birthdate' => $t["birthdate"],
                'address' => $t["address"],
            ]);
        }
        return $table;
    }
    /**
     * @Route("/e04/")
     */
    public function indexAction()
    {
        $table = $this->getTable();
        return $this->render('ex04::table.html.twig',[
            'table' => $table,
            'message' => "",
        ]);
    }

    /**
     * @Route("/e04/delete/{id}")
     */
    public function delete($id)
    {
        $table = $this->getTable();
        if(!is_numeric($id)){
            return $this->render('ex04::table.html.twig',[
                'table' => $table,
                'message' => "This id is not possible",
            ]);
        }
        $sql = "SELECT * from person_e04 WHERE id=". $id;
        $em = $this->get('doctrine.orm.default_entity_manager');
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        if (count($statement->fetchAll()) == 0){
            return $this->render('ex04::table.html.twig',[
                'table' => $table,
                'message' => "This id doesn't exist.",
            ]);
        }
        $sql = "DELETE from person_e04 WHERE id=". $id;
        $em = $this->get('doctrine.orm.default_entity_manager');
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $table = $this->getTable();
        return $this->render('ex04::table.html.twig',[
            'table' => $table,
            'message' => "Success the data is been delete.",
        ]);
    }
}
