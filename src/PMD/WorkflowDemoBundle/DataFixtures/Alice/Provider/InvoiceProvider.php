<?php

/*
 * This file is part of the PMDDemo package.
 *
 * (c) Piotr Minkina <projekty@piotrminkina.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMD\WorkflowDemoBundle\DataFixtures\Alice\Provider;

/**
 * Class InvoiceProvider
 * 
 * @author Piotr Minkina <projekty@piotrminkina.pl>
 * @package PMD\WorkflowDemoBundle\DataFixtures\Alice\Provider
 */
class InvoiceProvider
{
    /**
     * @return string
     */
    public function invoiceState()
    {
        $refInvoice = new \ReflectionClass(
            'PMD\WorkflowDemoBundle\Model\InvoiceInterface'
        );

        $constants = array_filter(
            array_flip($refInvoice->getConstants()),
            function ($constant) {
                return 'STATE_' === substr($constant, 0, 6);
            }
        );
        $constant = $constants[array_rand($constants)];

        return $refInvoice->getConstant($constant);
    }
}
