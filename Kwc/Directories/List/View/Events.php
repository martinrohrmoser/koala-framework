<?php
class Kwc_Directories_List_View_Events extends Kwc_Abstract_Events
{
    public function getListeners()
    {
        $ret = array();

        foreach (Kwc_Abstract::getComponentClasses() as $class) {
            if (in_array('Kwc_Directories_List_Component', Kwc_Abstract::getParentClasses($class)) || in_array('Kwc_Directories_List_Trl_Component', Kwc_Abstract::getParentClasses($class))) {
                if (Kwc_Abstract::hasChildComponentClass($class, 'child', 'view')
                    && $this->_class == Kwc_Abstract::getChildComponentClass($class, 'child', 'view')
                ) {
                    $directoryClasses = call_user_func(
                        array(strpos($class, '.') ? substr($class, 0, strpos($class, '.')) : $class, 'getItemDirectoryClasses'), $class
                    );
                    foreach ($directoryClasses as $directoryClass) {
                        $ret[] = array(
                            'class' => $directoryClass,
                            'event' => 'Kwc_Directories_List_EventItemInserted',
                            'callback' => 'onDirectoryRowInsert'
                        );
                        $ret[] = array(
                            'class' => $directoryClass,
                            'event' => 'Kwc_Directories_List_EventItemDeleted',
                            'callback' => 'onDirectoryRowDelete'
                        );
                        $ret[] = array(
                            'class' => $directoryClass,
                            'event' => 'Kwc_Directories_List_EventItemUpdated',
                            'callback' => 'onDirectoryRowUpdate'
                        );
                        $ret[] = array(
                            'class' => $directoryClass,
                            'event' => 'Kwc_Directories_List_EventItemsUpdated',
                            'callback' => 'onDirectoryModelUpdate'
                        );
                    }
                }
            }
        }
        return $ret;
    }

    private function _usesPartialId()
    {
        $partialClass = call_user_func(
            array(strpos($this->_class, '.') ? substr($this->_class, 0, strpos($this->_class, '.')) : $this->_class, 'getPartialClass'), $this->_class
        );
        return is_instance_of($partialClass, 'Kwf_Component_Partial_Id');
    }

    private function _getSubrootFromEvent(Kwc_Directories_List_EventItemAbstract $event)
    {
        $gen = Kwf_Component_Generator_Abstract::getInstance($event->class, 'detail');
        $datas = $gen->getChildData(null, array('id' => $event->itemId));
        if (!isset($datas[0])) return null;
        return $datas[0]->getSubroot();
    }

    public function onDirectoryRowInsert(Kwc_Directories_List_EventItemInserted $event)
    {
        $subroot = $this->_getSubrootFromEvent($event);
        if ($subroot) {
            $this->fireEvent(new Kwf_Component_Event_ComponentClass_ContentChanged($this->_class, $subroot));
            $this->fireEvent(new Kwf_Component_Event_ComponentClass_PartialsChanged($this->_class, $subroot));
            if (!$this->_usesPartialId()) {
                $this->fireEvent(new Kwf_Component_Event_ComponentClass_AllPartialChanged($this->_class, $subroot));
            }
        }
    }

    public function onDirectoryRowDelete(Kwc_Directories_List_EventItemDeleted $event)
    {
        $subroot = $this->_getSubrootFromEvent($event);
        if ($subroot) {
            $this->fireEvent(new Kwf_Component_Event_ComponentClass_ContentChanged($this->_class, $subroot));
            $this->fireEvent(new Kwf_Component_Event_ComponentClass_PartialsChanged($this->_class, $subroot));
            if ($this->_usesPartialId()) {
                $this->fireEvent(new Kwf_Component_Event_ComponentClass_PartialChanged($this->_class, $event->itemId));
            } else {
                $this->fireEvent(new Kwf_Component_Event_ComponentClass_AllPartialChanged($this->_class, $subroot));
            }
        }
    }

    public function onDirectoryRowUpdate(Kwc_Directories_List_EventItemUpdated $event)
    {
        $subroot = $this->_getSubrootFromEvent($event);
        if ($subroot) {
            $this->fireEvent(new Kwf_Component_Event_ComponentClass_ContentChanged($this->_class, $subroot));
            $this->fireEvent(new Kwf_Component_Event_ComponentClass_PartialsChanged($this->_class, $subroot));
            if ($this->_usesPartialId()) {
                $this->fireEvent(new Kwf_Component_Event_ComponentClass_PartialChanged($this->_class, $event->itemId));
            } else {
                $this->fireEvent(new Kwf_Component_Event_ComponentClass_AllPartialChanged($this->_class, $subroot));
            }
        }
    }

    public function onDirectoryModelUpdate(Kwc_Directories_List_EventItemsUpdated $event)
    {
        $this->fireEvent(new Kwf_Component_Event_ComponentClass_ContentChanged($this->_class));
        $this->fireEvent(new Kwf_Component_Event_ComponentClass_AllPartialChanged($this->_class));
        $this->fireEvent(new Kwf_Component_Event_ComponentClass_PartialsChanged($this->_class));
    }
}
