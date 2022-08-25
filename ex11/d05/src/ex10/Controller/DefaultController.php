<?php

namespace ex10\Controller;
use ex10\Entity\PersonORM;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    public $a = ["id", "username"];
     /**
     * @Route("/e10/")
     */
    public function home(){
        return $this->render('ex10:Default:index.html.twig', [
            "table_sql" => "",
            "table_orm" => "",
        ]);
    }

    /**
     * @Route("/e10/add/")
     */
    public function addFile()
    {
        $file = fopen(__DIR__ . "/../db.txt", "r");
        $line = fgets($file);
        $em = $this->getDoctrine()->getManager();
        $person = new PersonORM();
        $person->setUsername($line);
        $em->persist($person);
        $em->flush();
        $sql = "INSERT into person_e10(username) VALUES ('" 
            . $line . "');";
        $em = $this->get('doctrine.orm.default_entity_manager');
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $sql = "SELECT * from person_e10";
        $em = $this->get('doctrine.orm.default_entity_manager');
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $table = [];
        $table_o = [];
        foreach($statement->fetchAll() as $t){
            array_push($table, [
                'id' => $t["id"],
                'username' => $t["username"],
            ]);
        }

        $post = $this->getDoctrine()
        ->getRepository(PersonORM::class);
        $r = (array)$post->findAll();
        foreach($r as $t){
            $i = 0;
            $tab = [];
            foreach((array)$t as $e){
                $tab[$this->a[$i]] = $e;
                $i++;
            }
            array_push($table_o, [
                'id' => $tab["id"],
                'username' => $tab["username"],
            ]);
        }
        return $this->render('ex10:Default:index.html.twig', [
            "table_sql" => $table,
            "table_orm"  => $table_o,
        ]);
    }
}