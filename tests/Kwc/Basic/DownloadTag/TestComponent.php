<?php
class Kwc_Basic_DownloadTag_TestComponent extends Kwc_Basic_DownloadTag_Component
{
    public static function getSettings()
    {
        $ret = parent::getSettings();
        $ret['ownModel'] = 'Kwc_Basic_DownloadTag_TestModel';
        return $ret;
    }
}
