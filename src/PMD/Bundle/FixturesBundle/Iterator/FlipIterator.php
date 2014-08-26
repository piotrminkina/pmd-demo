<?php

/*
 * This file is part of the PMDDemo package.
 *
 * (c) Piotr Minkina <projekty@piotrminkina.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMD\Bundle\FixturesBundle\Iterator;

/**
 * Class FlipIterator
 * 
 * @author Piotr Minkina <projekty@piotrminkina.pl>
 * @package PMD\Bundle\FixturesBundle\Iterator
 */
class FlipIterator extends \IteratorIterator
{
    /**
     * @var integer
     */
    protected $position;

    /**
     * @var boolean
     */
    protected $valueAsKey;

    /**
     * @param \Traversable $iterator
     * @param bool $valueAsKey
     */
    public function __construct(\Traversable $iterator, $valueAsKey = false)
    {
        parent::__construct($iterator);
        $this->position = 0;
        $this->valueAsKey = $valueAsKey;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        parent::rewind();
        $this->position = 0;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return parent::key();
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->valueAsKey ? parent::current() : $this->position;
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        parent::next();
        $this->position++;
    }
}
