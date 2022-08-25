<?php

namespace ex00\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;

class DefaultController extends Controller
{
    /**
     * @Route("/e00/")
     */
    public function putButton()
    {
        return $this->render('ex00:Default:index.html.twig',[
            "error" => "",
            ]
        );
    }

    /**
     * @Route("/e00/create/")
     */
    public function indexAction()
    {
        $sql = "CREATE TABLE person("
            . "id int NOT NULL AUTO_INCREMENT,"
            . "username varchar(255) UNIQUE,"
            . "name varchar(255),"
            . "email varchar(255) UNIQUE,"
            . "enable boolean,"
            . "birthdate datetime,"
            . "address LONGTEXT,"
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
        return $this->render('ex00:Default:index.html.twig',[
            "error" => $error,
            ]
        );
    }
    
}