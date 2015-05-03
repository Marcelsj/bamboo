<?php

/*
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014-2015 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 * @author Elcodi Team <tech@elcodi.com>
 */

namespace Elcodi\Common\VisithorBridgeBundle\Visithor;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\HttpKernel\KernelInterface;
use Visithor\Bundle\Environment\Interfaces\EnvironmentBuilderInterface;

/**
 * Class EnvironmentBuilder
 */
class EnvironmentBuilder implements EnvironmentBuilderInterface
{
    /**
     * @var boolean
     *
     * Debug mode
     */

    protected $debug = false;

    /**
     * Set up environment
     *
     * @param KernelInterface $kernel Kernel
     *
     * @return $this Self object
     */
    public function setUp(KernelInterface $kernel)
    {
        gc_collect_cycles();

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $this
            ->checkDoctrineConnection($kernel)
            ->executeCommand($application, 'doctrine:database:drop', [
                '--force' => true,
            ])
            ->checkDoctrineConnection($kernel)
            ->executeCommand($application, 'doctrine:database:create')
            ->executeCommand($application, 'doctrine:schema:create')
            ->executeCommand($application, 'doctrine:fixtures:load', [
                '--fixtures' => $kernel
                        ->getRootDir() . '/../src/Elcodi/Fixtures',
            ])
            ->executeCommand($application, 'elcodi:templates:load')
            ->executeCommand($application, 'elcodi:templates:enable', [
                'template' => 'StoreTemplateBundle',
            ])
            ->executeCommand($application, 'elcodi:plugins:load')
            ->executeCommand($application, 'assets:install')
            ->executeCommand($application, 'assetic:dump');
    }

    /**
     * Tear down environment
     *
     * @param KernelInterface $kernel Kernel
     *
     * @return $this Self object
     */
    public function tearDown(KernelInterface $kernel)
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $this
            ->checkDoctrineConnection($kernel)
            ->executeCommand($application, 'doctrine:database:drop', [
                '--force' => true,
            ]);

    }

    /**
     * Execute a command
     *
     * @param Application $application Application
     * @param string      $command     Command
     * @param array       $parameters  Parameters
     *
     * @return $this Self object
     */
    protected function executeCommand(
        Application $application,
        $command,
        array $parameters = []
    )
    {
        $definition = array_merge(
            [
                'command'          => $command,
                '--no-interaction' => true,
                '--env'            => 'test'
            ], $parameters
        );

        if (!$this->debug) {
            $definition['--quiet'] = true;
        }

        $application->run(new ArrayInput($definition));

        return $this;
    }

    /**
     * Check the doctrine connection
     *
     * @param KernelInterface $kernel Kernel
     *
     * @return $this Self object
     */
    protected function checkDoctrineConnection(KernelInterface $kernel)
    {
        /**
         * @var Connection $doctrineConnection
         */
        $doctrineConnection = $kernel
            ->getContainer()
            ->get('doctrine')
            ->getConnection();

        if ($doctrineConnection->isConnected()) {
            $doctrineConnection->close();
        }

        return $this;
    }
}
