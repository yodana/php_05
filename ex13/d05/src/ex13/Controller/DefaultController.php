<?php

namespace ex13\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ex13\Entity\Employee;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function findSuperior($position){
        $p = ["manager", "account_manager", "qa_manager", "dev_manager",
        "ceo", "coo", "backend_dev", "frontend_dev", "qa_tester"];
        $i = array_search($position, $p) - 1;
        $post = $this->getDoctrine()
        ->getRepository(Employee::class);
        $r = (array)$post->findAll();
        while($i >= 0){
            foreach ($r as $product) {
                if ($p[$i] == $product->getPosition()){
                    return $product;
                }
            }
            $i--;
        }
        return NULL;
    }

    public function getTable(){
        $table = [];
        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();
        $fields = array('d.id', 'd.lastname', 'd.firstname', 'd.email', 'd.birthdate', 'd.active', 'd.salary', 'd.employeeSince', 'd.employeeUntil',
        'd.hours', 'd.position');
        try{
            $result = $qb
            ->select($fields)
            ->from('ex13:Employee', 'd')
            ->leftJoin('ex13:Employee', 'e', 'WITH', 'e.id = d.superiors');
            $result = $result->getQuery()->getResult();
            var_dump($result);
        }
        catch(\Exception $e){
            echo 'Exception reçue : ',  $e->getMessage();
        }
        foreach($result as $t){
            array_push($table, [
                'id' => $t["id"],
                'firstname' => $t["firstname"],
                'lastname' => $t["lastname"],
                'email' => $t["email"],
                'active' => $t["active"],
                'birthdate' => $t["birthdate"]->format('Y-M-D'),
                'employee_since' => $t["employeeSince"]->format('Y-M-D'),
                'employee_until' => $t["employeeUntil"]->format('Y-M-D'),
                'salary' => $t["salary"],
                'hours' => $t["hours"],
                'position' => $t["position"],
                'name_superior' => $t["lastname"],
            ]);
        }
        //var_dump($table[]["amount"]->getValues());
        return $table;
    }

    public function getForm(){
        $form = $this->createFormBuilder()
        ->add('firstname', TypeTextType::class)
        ->add('lastname', TypeTextType::class)
        ->add('email', EmailType::class)
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
        ->add('salary', IntegerType::class)
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
        return $form;
    }
    /**
     * @Route("/e13/")
     */
    public function indexAction(Request $request)
    {
        $error = "";
        $form = $this->getForm();
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $em = $this->getDoctrine()->getManager();
            $person = new employee();
            $superior = $this->findSuperior($form["position"]->getData());
            $person->setFirstname($form["firstname"]->getData());
            $person->setLastname($form["lastname"]->getData());
            $person->setEmail($form["email"]->getData());
            $person->setActive($form["active"]->getData());
            $person->setBirthdate($form["birthdate"]->getData());
            $person->setEmployeeSince($form["employee_since"]->getData());
            $person->setEmployeeUntil($form["employee_until"]->getData());
            $person->setSalary($form["salary"]->getData());
            $person->setPosition($form["position"]->getData());
            $person->setHours($form["hours"]->getData());
            var_dump($superior);
            if($superior == NULL)
                $superior = $person;
            $person->setSuperiors($superior);
            $em->persist($person);
            $validator = $this->get('validator');
            $errors = $validator->validate($person);
            if(count($errors) == 0)
                $em->flush();
            else
                $error = $errors[0]->getMessage();
        }
        return $this->render('ex13:Default:index.html.twig', [
            'form' => $form->createView(),
            'table' => $this->getTable(),
            'button' => false,
            'error' => $error,
        ]);
    }
    
    /**
     * @Route("/e13/delete/{id}/")
     */
    public function delete($id)
    {
        $table = $this->getTable();
        if(!is_numeric($id)){
            return $this->render('ex13:Default:index.html.twig',[
                'table' => $table,
                'form' => $this->getForm()->createView(),
                'button' => true,
                'error' => "This id is not possible.",
            ]);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Employee::class);
        $product = $repository->find($id);
        if (!$product){
            return $this->render('ex13:Default:index.html.twig',[
                'table' => $table,
                'form' => $this->getForm()->createView(),
                'button' => true,
                'error' => "This id doesn't exist.",
            ]);
        }
        $entityManager->remove($product);
        $entityManager->flush();
        $table = $this->getTable();
        return $this->render('ex13:Default:index.html.twig',[
            'table' => $table,
            'form' => $this->getForm()->createView(),
            'button' => true,
            'error' => "Success: the employee has been destroyed",
        ]);
    }

}
