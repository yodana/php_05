<?php

namespace ex13\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ex13\Entity\employee;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function getTable(){
        $table = [];
        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();
        $fields = array('d.id', 'd.username', 'd.name', 'd.email', 'd.birthdate', 'd.enable');
        try{
            $result = $qb
            ->select($fields)
            ->from('ex13:employee', 'd');
            $result = $result->getQuery()->getResult();
        }
        catch(\Exception $e){
            echo 'Exception reçue : ',  $e->getMessage();
        }
        foreach($result as $t){
            array_push($table, [
                'username' => $t["username"],
                'name' => $t["name"],
                'email' => $t["email"],
                'enable' => $t["enable"],
                'birthdate' => $t["birthdate"]->format('Y-M-D'),
            ]);
        }
        //var_dump($table[]["amount"]->getValues());
        return $table;
    }

    /**
     * @Route("/e13/")
     */
    public function indexAction(Request $request)
    {
        $error = "";
        $form = $this->createFormBuilder()
            ->add('firstname', TypeTextType::class)
            ->add('lastname', TypeTextType::class)
            ->add('email', TypeTextType::class)
            ->add('active', ChoiceType::class,
                [ 
                    'choices' => 
                    [   
                        'Yes' => true,
                        'No' => false,        
                    ],
                ])
            ->add('birthdate', DateType::class)
            ->add('employee_since', DateType::class)
            ->add('employee_until', DateType::class)
            ->add('hours', ChoiceType::class,
                [ 
                    'choices' => 
                    [   
                        '8' => '8',
                        '6' => '6',    
                        '4' => '4',     
                    ],
                ])
            ->add('position', ChoiceType::class,
                [ 
                    'choices' => 
                    [   
                        'manager' => 'manager',
                        'account_manager' => 'account_manager',    
                        'qa_manager' => 'qa_manager',
                        'dev_manager' => 'dev_manager',
                        'ceo' => 'ceo',
                        'coo' => 'coo',
                        'backend_dev' => 'backend_dev',
                        'frontend_dev' => 'frontend_dev',
                        'qa_tester' => 'qa_tester',    
                    ],
                ])
            ->add('save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $person = new employee();
            $person->setUsername($form["username"]->getData());
            $person->setName($form["name"]->getData());
            $person->setEmail($form["email"]->getData());
            $person->setEnable($form["enable"]->getData());
            $person->setBirthdate($form["birthdate"]->getData());
            $em->persist($person);
            $validator = $this->get('validator');
            $errors = $validator->validate($person);
            if(count($errors) == 0)
                $em->flush();
            else
                $error = "Error: Username or Email exist already";
        }
        return $this->render('ex13:Default:index.html.twig', [
            'form' => $form->createView(),
            'table' => $this->getTable(),
            'error' => $error,
        ]);
    }
}
