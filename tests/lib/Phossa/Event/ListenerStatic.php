<?php

namespace Phossa\Event;

class ListenerStatic implements EventListenerStaticInterface
{
    public static function __callStatic($name, $arguments) {
        $evt = $arguments[0];
        $evt->setProperty('s_' . $name, 'Static ' . $name);
    }

    public static function getEventsListening()
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

