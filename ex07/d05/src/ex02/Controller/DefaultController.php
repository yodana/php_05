<?php

namespace ex02\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/e02/")
     */
    public function indexAction(Request $request)
    {
        $sql = "CREATE TABLE IF NOT EXISTS person_e02("
            . "id int NOT NULL AUTO_INCREMENT,"
            . "username varchar(255) UNIQUE,"
            . "name varchar(255),"
            . "email varchar(255) UNIQUE,"
            . "enable boolean,"
            . "birthdate datetime,"
            . "address LONGTEXT,"
            . "PRIMARY KEY (id)"
            . ");";
        $em = $this->get('doctrine.orm.default_entity_manager');
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $form = $this->createFormBuilder()
            ->add('username', TypeTextType::class)
            ->add('name', TypeTextType::class)
            ->add('email', TypeTextType::class)
            ->add('enable', ChoiceType::class,
                [ 
                    'choices' => 
                    [   
                        'Yes' => true,
                        'No' => false,        
                    ],
                ])
            ->add('birthdate', DateType::class)
            ->add('address', TypeTextType::class)
            ->add('save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        $sql_user = "SELECT * FROM person_e07 WHERE username ='".$form["username"]->getData()."'";
        $sql_mail = "SELECT * FROM person_e07 WHERE email ='".$form["email"]->getData()."'";
        $statement_u = $em->getConnection()->prepare($sql_user);
        $statement_u->execute();
        $statement_m = $em->getConnection()->prepare($sql_mail);
        $statement_m->execute();
        if ($form->isValid() && $form->isSubmitted()){
            if (count($statement_u->fetchAll()) == 0 && count($statement_m->fetchAll()) == 0){
            $sql_insert = "INSERT into person_e07(username, name, email, enable, birthdate, address) VALUES ('" 
            . $form["username"]->getData() 
            . "','" . $form["name"]->getData() 
            . "','" . $form["email"]->getData() 
            . "','" . $form["enable"]->getData()
            . "','" . $form["birthdate"]->getData()->format('Y-m-d')
            . "','" . $form["address"]->getData() . "')";
                $statement = $em->getConnection()->prepare($sql_insert);
                $statement->execute();
            }
            else{
                return $this->render('ex02:Default:index.html.twig', [
                    'form' => $form->createView(),
                    'error' => "User or Email exist already",
                ]);
            }
        }
        return $this->render('ex02:Default:index.html.twig', [
            'form' => $form->createView(),
            'error' => "",
        ]);
    }

    /**
     * @Route("/e02/show/")
     */
    public function showTable(){
        $sql = "SELECT * from person_e02";
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
        return $this->render('ex02::table.html.twig', [
            'table' => $table,
        ]);
    }
}
