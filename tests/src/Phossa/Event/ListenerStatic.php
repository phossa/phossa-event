<?php

namespace Phossa\Event;

use Phossa\Event\Interfaces\EventListenerStaticInterface;

class ListenerStatic implements EventListenerStaticInterface
{
    /*
     * able to execute all methods defined in getEventsListeningStatically()
     */
    public static function __callStatic($name, $arguments) {
        $evt = $arguments[0];
        $evt->setProperty('s_' . $name, 'Static ' . $name);
    }

    public static function getEventsListeningStatically()
    {
        return [
            'evtTest1' => 'testC',
            'evtTest2' => [ 'testDD', 20 ],
            'evtTest3' => [
                [ 'testA', 70 ],
                [ 'testB', 50 ]
            ]
        ];
    }
}

