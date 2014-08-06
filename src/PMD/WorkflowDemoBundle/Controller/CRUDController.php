<?php

/*
 * This file is part of the PMDDemo package.
 *
 * (c) Piotr Minkina <projekty@piotrminkina.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMD\WorkflowDemoBundle\Controller;

/**
 * Class CRUDController
 * 
 * @author Piotr Minkina <projekty@piotrminkina.pl>
 * @package PMD\WorkflowDemoBundle\Controller
 */
class CRUDController
{
    /**
     * @param object[] $objects
     * @return array
     */
    public function listAction($objects)
    {
        return array(
            'objects' => $objects,
        );
    }

    /**
     * @return array
     */
    public function createAction()
    {
        // use handler to handle request and create object
        $object = null;

        return array(
            'object' => $object,
        );
    }

    /**
     * @param object $object
     * @return array
     */
    public function readAction($object)
    {
        return array(
            'object' => $object,
        );
    }

    /**
     * @param object $object
     * @return array
     */
    public function updateAction($object)
    {
        // use handler to handle request and update object

        return array(
            'object' => $object,
        );
    }

    /**
     * @param object $object
     * @return array
     */
    public function deleteAction($object)
    {
        // use handler to handle request and delete object

        return array(
            'object' => $object,
        );
    }
}
