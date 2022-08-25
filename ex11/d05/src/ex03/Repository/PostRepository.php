<?php

namespace ex03\Repository;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class PostRepository extends \Doctrine\ORM\EntityRepository
{

    public function createTable($application){
        $application->setAutoExit(false);
        $input = new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force' => true,
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);
    }
}
