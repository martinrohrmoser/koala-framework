<?php
class Kwc_Basic_Headline_Component extends Kwc_Abstract
{
    public static function getSettings()
    {
        $ret = array_merge(parent::getSettings(), array(
            'componentName' => trlKwfStatic('Headline'),
            'componentIcon' => new Kwf_Asset('text_padding_top'),
            'ownModel'      => 'Kwc_Basic_Headline_Model',
            'cssClass'      => 'webStandard',
            'extConfig'     => 'Kwf_Component_Abstract_ExtConfig_Form'
        ));
        $ret['throwHasContentChangedOnRowColumnsUpdate'] = array('headline1', 'headline2', 'headline_type');
        return $ret;
    }

    public function getTemplateVars()
    {
        $ret = parent::getTemplateVars();
        $ret['headline1'] = $this->_getRow()->headline1;
        $ret['headline2'] = $this->_getRow()->headline2;
        $ret['headlineType'] = !$this->getRow()->headline_type ? 'h1' : $this->getRow()->headline_type;
        return $ret;
    }

    public function hasContent()
    {
        if (trim($this->_getRow()->headline1) != "") {
            return true;
        }
        return false;
    }
}