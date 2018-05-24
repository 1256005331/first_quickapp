<?php
class PublicAction extends Action
{
    Public function code()
    {
        import('ORG.Util.Verify');
        $Verify = new Verify();
        $Verify->fontSize = 14;
        $Verify->expire   = 60;
        $Verify->imageW   = 120;
        $Verify->imageH   = 38;
        $Verify->length   = 4;
        $Verify->useNoise = false;
        $Verify->codeSet = '0123456789';
        $Verify->entry();
    }
}