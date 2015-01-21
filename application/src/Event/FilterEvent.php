<?php
namespace Omeka\Event;

use Zend\EventManager\Event;

/**
 * Filter event.
 *
 * Trigger a filter event like so:
 * 
 * <code>
 * $arg = array('foo');
 * $event = new FilterEvent;
 * $event->setArg($arg);
 * $this->getEventManager()->trigger('myFilterEvent', $event);
 * $arg = $event->getArg();
 * </code>
 */
class FilterEvent extends Event
{
    /**
     * Filter triggered by the resource discriminator map Doctrine listener,
     * allowing modules to extend the entity resource interface.
     */
    const RESOURCE_DISCRIMINATOR_MAP = 'resource_discriminator_map';

    /**
     * @var mixed The argument to filter.
     */
    protected $arg;

    /**
     * Set the argument to filter.
     *
     * @param mixed $arg
     */
    public function setArg($arg)
    {
        $this->arg = $arg;
    }

    /**
     * Get the argument to filter.
     *
     * @return mixed $arg
     */
    public function getArg()
    {
        return $this->arg;
    }
}
