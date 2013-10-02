<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activity Controller.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
class ControllerActivity extends Library\ControllerModel
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->registerCallback('before.add', array($this, 'setIp'));
    }

    protected function _actionPurge(KCommandContext $context)
    {
        if (!$this->getModel()->getTable()->getDatabase()->execute($this->getModel()->getPurgeQuery()))
        {
            $context->setError(new KControllerExceptionActionFailed(
                'Delete Action Failed', KHttpResponse::INTERNAL_SERVER_ERROR
            ));
        }
        else $context->status = KHttpResponse::NO_CONTENT;
    }

    public function setIp(Library\CommandContext $context)
    {
        $context->request->data->ip = $this->getObject('application')->getRequest()->getAddress();
    }
}
