<?php

function pgz(\app\components\Point $knp, \app\components\Polar $notch)
{
    $ret = new \app\components\Grc(0, 0, 0);
    $ret->x = $knp->x + $notch->d * cos($notch->a);
    $ret->y = $knp->y + $notch->d * sin($notch->a);
    $ret->h = $knp->h + $notch->d * tan($notch->e);
    return $ret;
}

function ogz(\app\components\Point $tgt, \app\components\Point $op)
{
    $ret = new Polar(new \app\components\Directional(0,0),0,new app\components\Sdu('+', 0, 0));
    $dx = $tgt->x - $op->x;
    $dy = $tgt->y - $op->y;
    $dh = $tgt->h - $op->h;
    $ret->d = sqrt($dx*$dx + $dy*$dy);
    $ret->e = atan($dh/$ret->d);
    $rumb = acos($dx/$ret->d);
    $$ret->a = $rumb<0?2*pi()+$rumb:$rumb;
}