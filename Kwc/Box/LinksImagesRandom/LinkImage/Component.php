<?php
class Kwc_Box_LinksImagesRandom_LinkImage_Component extends Kwc_Composite_LinkImage_Component
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        $ret['generators']['child']['component']['image'] = 'Kwc_Box_LinksImagesRandom_LinkImage_Image_Component';
        return $ret;
    }
}
