<?php

namespace ex06\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function getTable(){
        $sql = "SELECT * from person_e06";
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
     * @Route("/e06/")
     */
    public function indexAction()
    {
        $table = $this->getTable();
        return $this->render('ex06::table.html.twig',[
            'table' => $table,
            "form" => "",
            'message' => "",
        ]);
    }

    /**
     * @Route("/e06/update/{id}")
     */
    public function update($id, Request $request)
    {
        $sql = "SELECT * from person_e06 WHERE id=" . $id;
        $em = $this->get('doctrine.orm.default_entity_manager');
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $table = $statement->fetchAll()[0];
        $form = $this->createFormBuilder()
            ->add('username', TypeTextType::class, 
                [   
                    'attr' => array(
                        'value' => $table["username"]
                    )
                ])
            ->add('name', TypeTextType::class, 
                [   
                    'attr' => array(
                        'value' => $table["name"]
                    )
                ])
            ->add('email', TypeTextType::class, 
                [   
                    'attr' => array(
                        'value' => $table["email"]
                    )
                ])
            ->add('enable', ChoiceType::class,
                [ 
                    'choices' => 
                    [   
                        'Yes' => true,
                        'No' => false,        
                    ],
                    'attr' => array(
                        'value' => $table["enable"]
                    )
                ])
            ->add('birthdate', DateType::class, 
                [   
                    'data' => new \DateTime($table["birthdate"])
                ])
            ->add('address', TypeTextType::class, 
                [   
                    'attr' => array(
                        'value' => $table["address"]
                    )
                ])
            ->add('update', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
            if ($form->isValid() && $form->isSubmitted()){
                $sql_update = "UPDATE person_e06 SET
                username='" . $form["username"]->getData() . "',
                name='" . $form["name"]->getData() . "',
                email='" . $form["email"]->getData() . "',
                enable='" . $form["enable"]->getData() . "',
                birthdate='" . $form["birthdate"]->getData()->format('Y-m-d') . "',
                address='" . $form["address"]->getData() . "' WHERE id=" . $id;
                $statement = $em->getConnection()->prepare($sql_update);
                try{
                    $statement->execute();
                }
                catch(\Exception $e){
                    return $this->render('ex06::table.html.twig',[
                        'table' => $this->getTable(),
                        'message' => $e,
                    ]);
                }
                return $this->render('ex06::table.html.twig',[
                    'table' => $this->getTable(),
                    'message' => "Update success.",
                ]);
            }
        return $this->render('ex06::form.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
