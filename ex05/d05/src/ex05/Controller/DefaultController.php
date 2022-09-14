<?php

namespace ex05\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use ex05\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    public function getTable(){
        $table = [];
        $a = ["id", "username", "name", "email", "enable", "birthdate", "address"];
        $post = $this->getDoctrine()
        ->getRepository(Post::class);
        $r = (array)$post->findAll();
        foreach($r as $t){
            $i = 0;
            $tab = [];
            foreach((array)$t as $e){
                $tab[$a[$i]] = $e;
                $i++;
            }
            array_push($table, [
                'id' => $tab["id"],
                'username' => $tab["username"],
                'name' => $tab["name"],
                'email' => $tab["email"],
                'enable' => $tab["enable"],
                'birthdate' => $tab["birthdate"]->format('Y-m-d'),
                'address' => $tab["address"],
            ]);
        }
        return $table;
    }

    /**
     * @Route("/e05/")
     */
    public function indexAction()
    {
        $application = new ConsoleApplication($this->get('kernel'));
        $application->setAutoExit(false);
        $error =  "Success: the table is created succefully";
        $input = new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => true,
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();
        $table = $this->getTable();
        return $this->render('ex05::table.html.twig',[
            'table' => $table,
            'message' => "",
        ]);
    }

    /**
     * @Route("/e05/delete/{id}/")
     */
    public function delete($id)
    {
        $table = $this->getTable();
        if(!is_numeric($id)){
            return $this->render('ex05::table.html.twig',[
                'table' => $table,
                'message' => "This id is not possible.",
            ]);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Post::class);
        $product = $repository->find($id);
        if (!$product){
            return $this->render('ex05::table.html.twig',[
                'table' => $table,
                'message' => "This id doesn't exist.",
            ]);
        }
        $entityManager->remove($product);
        $entityManager->flush();
        $table = $this->getTable();
        return $this->render('ex05::table.html.twig',[
            'table' => $table,
            'message' => "Success the data is been delete.",
        ]);
    }
}
