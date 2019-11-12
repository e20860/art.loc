<?php

namespace app\components;

use app\components\Directional;
use app\components\Point;
use app\components\Polar;
use app\components\Sdu;
use app\components\Grc;

/**
 * Description of Topo
 *
 * @author Admin
 */
class Topo {
    public static function pgz(Point $knp, Polar $notch)
    {
        $ret = new Grc(0, 0, 0);
        $ret->x = $knp->x + $notch->d * cos($notch->a);
        $ret->y = $knp->y + $notch->d * sin($notch->a);
        $ret->h = $knp->h + $notch->d * tan($notch->e);
        return $ret;
    }
    
    public static function ogz(Point $tgt, Point $op)
    {
        $ret = new Polar(new Directional(0,0),0,new Sdu('+', 0, 0));
        $dx = $tgt->x - $op->x;
        $dy = $tgt->y - $op->y;
        $dh = $tgt->h - $op->h;
        $ret->d = sqrt($dx*$dx + $dy*$dy);
        $ret->e = atan($dh/$ret->d);
        $rumb = acos($dx/$ret->d);
        $ret->a = $rumb<0?2*pi()+$rumb:$rumb;
        return $ret;        
    }
}
