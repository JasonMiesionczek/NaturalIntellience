<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 5/14/16
 * Time: 11:07 PM
 */

namespace AppBundle\EventListener;


use AppBundle\Entity\User;
use FOS\UserBundle\Event\UserEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\FOSUserEvents;

class ApiTokenListener implements EventSubscriberInterface
{

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrationInit'
        );
    }

    public function onRegistrationInit(UserEvent $userEvent)
    {
        /** @var User $user */
        $user = $userEvent->getUser();
        $user->setApiToken(md5($user->getEmail() . $user->getPassword() . $user->getUsername()));
    }
}