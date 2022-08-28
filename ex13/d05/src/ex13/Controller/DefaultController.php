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
    public function findSuperior($position, $person){
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
        return $person;
    }

    public function getTable(){
        $table = [];
        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();
        $fields = array('d');
        try{
            $result = $qb
            ->select($fields)
            ->from('ex13:Employee', 'd');
            $result = $result->getQuery()->getResult();
        }
        catch(\Exception $e){
            echo 'Exception reçue : ',  $e->getMessage();
        }
        foreach($result as $t){
            $t->setSuperiors($this->findSuperior($t->getPosition(), $t));
            try{
                $entityManager->flush();
            }
            catch(\Exception $e){
                echo "Message Error = " . $e;
            }
            array_push($table, [
                'id' => $t->getId(),
                'firstname' => $t->getFirstname(),
                'lastname' => $t->getLastname(),
                'email' => $t->getEmail(),
                'active' => $t->getActive(),
                'birthdate' => $t->getBirthdate()->format('Y-M-D'),
                'employee_since' => $t->getEmployeeSince()->format('Y-M-D'),
                'employee_until' => $t->getEmployeeUntil()->format('Y-M-D'),
                'salary' => $t->getSalary(),
                'hours' => $t->getHours(),
                'position' => $t->getPosition(),
                'name_superior' => $t->getSuperiors()->getLastname(),
            ]);
        }
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
            $person->setSuperiors($this->findSuperior($form["position"]->getData(), $person));
            $validator = $this->get('validator');
            $errors = $validator->validate($person);
            if(count($errors) == 0){
                $em->persist($person);
                $em->flush();
            }
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
        if(!is_numeric($id)){
            return $this->render('ex13:Default:index.html.twig',[
                'table' => '',
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
                'table' => '',
                'form' => $this->getForm()->createView(),
                'button' => true,
                'error' => "This id doesn't exist.",
            ]);
        }
        $entityManager->remove($product);
        try{
            $entityManager->flush();
        }
        catch(\Exception $e){
            echo "Message Error = " . $e;
        }
        return $this->render('ex13:Default:index.html.twig',[
            'table' => '',
            'form' => $this->getForm()->createView(),
            'button' => true,
            'error' => "Success: the employee has been destroyed",
        ]);
    }
    
    /**
     * @Route("/e13/update/{id}")
    */
    public function update($id, Request $request){
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
        $employee = $repository->find($id);
        if (!$employee){
            return $this->render('ex13:Default:index.html.twig',[
                'table' => $table,
                'form' => $this->getForm()->createView(),
                'button' => true,
                'error' => "This id doesn't exist.",
            ]);
        }
        $form = $this->createFormBuilder()
        ->add('lastname', TypeTextType::class, 
            [   
                'attr' => array(
                    'value' => $employee->getLastname()
                )
            ])
        ->add('firstname', TypeTextType::class, 
            [   
                'attr' => array(
                    'value' => $employee->getFirstName()
                )
            ])
        ->add('email', TypeTextType::class, 
            [   
                'attr' => array(
                    'value' => $employee->getEmail()
                )
            ])
        ->add('active', ChoiceType::class,
            [ 
                'choices' => 
                [   
                    'Yes' => true,
                    'No' => false,        
                ],
                'attr' => array(
                    'value' => $employee->getActive()
                )
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
                'attr' => array(
                    'value' => $employee->getPosition()
                )
            ])
        ->add('hours', ChoiceType::class,
            [ 
                'choices' => 
                [   
                    '8' => '8',
                    '6' => '6',    
                    '4' => '4',     
                ],
                'attr' => array(
                    'value' => $employee->getHours()
                )
            ])
        ->add('birthdate', DateType::class, 
            [   
                'data' => $employee->getBirthdate()
            ])
        ->add('employee_since', DateType::class, 
            [   
                'data' => $employee->getEmployeeSince()
            ])
        ->add('employee_until', DateType::class, 
            [   
                'data' => $employee->getEmployeeUntil()
            ])
        ->add('salary', IntegerType::class, 
            [   
                'attr' => array(
                    'value' => $employee->getSalary()
                )
            ])
        ->add('update', SubmitType::class)
        ->getForm();
        $error = "Success";
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $employee->setLastname($form["lastname"]->getData());
            $employee->setFirstName($form["firstname"]->getData());
            $employee->setEmail($form["email"]->getData());
            $employee->setActive($form["active"]->getData());
            $employee->setBirthdate($form["birthdate"]->getData());
            $employee->setPosition($form["position"]->getData());
            $employee->setHours($form["hours"]->getData());
            $employee->setEmployeeSince($form["employee_since"]->getData());
            $employee->setEmployeeUntil($form["employee_until"]->getData());
            $employee->setSalary($form["salary"]->getData());
            try{
                $validator = $this->get('validator');
                $errors = $validator->validate($employee);
                if(count($errors) == 0){
                    $entityManager->persist($employee);
                    $entityManager->flush();
                }
                else
                    $error = $errors[0]->getMessage();
            }
            catch(\Exception $e){
                return $this->render('ex13::update.html.twig',[
                    'table' => $table,
                    'form' => $form->createView(),
                    'button' => true,
                    'error' => "Error update",
                ]);
            }
            return $this->render('ex13:Default:index.html.twig',[
                "table" => $table,
                "form" => $this->getForm()->createView(),
                'button' => true,
                'error' => $error,
            ]);
        }
        return $this->render('ex13::update.html.twig',[
                "table" => '',
                "form" => $form->createView(),
                'button' => true,
                'error' => "",
        ]);
    }
}
