<?php
class Kwf_Component_Events_RequestHttpsCache extends Kwf_Events_Subscriber
{
    public function getListeners()
    {
        $ret = array();
        foreach (Kwc_Abstract::getComponentClasses() as $c) {
            if (Kwc_Abstract::getFlag($c, 'requestHttps')) {
                $ret[] = array(
                    'class' => $c,
                    'event' => 'Kwf_Component_Event_Component_Added',
                    'callback' => 'onComponentAdded'
                );
            }
        }
        return $ret;
    }

    public function onComponentAdded(Kwf_Component_Event_Component_Added $ev)
    {
        $page = $ev->component->getPage();
        if ($page) {
            $cacheId = 'reqHttps-'.$page->componentId;
            Kwf_Cache_Simple::delete($cacheId);
        }
    }
}
