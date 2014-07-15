<?php

/*
 * This file is part of the PMDWorkflowDemo package.
 *
 * (c) Piotr Minkina <projekty@piotrminkina.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PMD\WorkflowDemoBundle\Model;

use PMD\StateMachineBundle\Model\StatefulInterface;

/**
 * Interface InvoiceInterface
 * 
 * @author Piotr Minkina <projekty@piotrminkina.pl>
 * @package PMD\WorkflowDemoBundle\Model
 */
interface InvoiceInterface extends StatefulInterface
{
    const STATE_REGISTERED = 'registered';

    const STATE_CORRECTED = 'corrected';

    const STATE_ACCEPTED = 'accepted';

    const STATE_PAID = 'paid';

    const STATE_CANCELED = 'canceled';

    const STATE_REMOVED = 'removed';

    const STATE_EXPIRED = 'expired';
}
