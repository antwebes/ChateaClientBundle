<?php

namespace Ant\Bundle\ChateaClientBundle\Event;

final class ChateaClientEvents
{
    /**
     * The user.register.success dispatch a SUCCESS event when the form is valid before saving the user
     *
     * The event listener receives an
     * Ant\Bundle\ChateaClientBundle\Event\UserEvent instance.
     *
     * @var string
     */
    const USER_REGISTER_SUCCESS = 'user.register.success';
}