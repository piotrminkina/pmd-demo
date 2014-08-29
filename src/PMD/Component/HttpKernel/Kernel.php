<?php

/*
 * This file is part of the PMDDemo package.
 *
 * (c) Piotr Minkina <projekty@piotrminkina.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMD\Component\HttpKernel;

use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Class Kernel
 * 
 * @author Piotr Minkina <projekty@piotrminkina.pl>
 * @package PMD\Component\HttpKernel
 */
abstract class Kernel extends BaseKernel
{
    /**
     * @var string|null
     */
    protected $workDir;

    /**
     * @inheritdoc
     */
    public function __construct($environment, $debug)
    {
        parent::__construct(
            $environment,
            $debug
        );

        $this->workDir = $this->getWorkDir();
    }

    /**
     * @inheritdoc
     */
    public function getCacheDir()
    {
        return $this->workDir.'/cache/'.$this->environment;
    }

    /**
     * @inheritdoc
     */
    public function getLogDir()
    {
        return $this->workDir.'/logs';
    }

    /**
     * Gets the application work dir.
     *
     * @return string The application work dir
     */
    public function getWorkDir()
    {
        if (null === $this->workDir) {
            $this->workDir = dirname($this->getRootDir()).'/var';
        }

        return $this->workDir;
    }
}
