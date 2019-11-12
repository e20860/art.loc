<?php

namespace models;

//require 'C:\Distr\openserver\OSPanel\domains\art.loc\components\functions.php';

use app\components\Point;
use app\components\Polar;
use app\components\Grc;
use app\components\Directional;
use app\components\Sdu;
use app\components\Knp;
use app\components\Topo;

/**
 * Description of KnpTest
 *
 * @author Admin
 */
class KnpTest extends \Codeception\Test\Unit{
    
    public function testKnp()
    {
        $knp5 = new Knp('КНП-5', new Grc(10000,20000,100));
        $knp5->unit = '5 батр';
        $this->assertEquals('5 батр',$knp5->unit);
        $correctName ='КНП 5 батр, X= 10000, Y= 20000, H= 100';
        $this->assertEquals($correctName, (string) $knp5);
    }
            
    function testPgz()
    {
        $knp = new Knp('КНП-5', new Grc(10000, 20000, 100));
        $notch = new Polar(new Directional(7, 50),1000,new Sdu('+',0,10));
        $tgt = Topo::pgz($knp,$notch);
        $this->assertEquals(10707,round($tgt->x));
        $this->assertEquals(20707,round($tgt->y));
        $this->assertEquals(110,round($tgt->h));
        $notch1 = new Polar(new Directional(52, 50), 1000, new Sdu('-',0,10));
        $tgt1 = Topo::pgz($knp, $notch1);
        $this->assertEquals(10707,round($tgt1->x));
        $this->assertEquals(19293,round($tgt1->y));
        $this->assertEquals(90,round($tgt1->h));
        $notch1->a = pi()*0.75;
        $tgt2 = Topo::pgz($knp, $notch1);
        $this->assertEquals(9293,round($tgt2->x));
        $this->assertEquals(20707,round($tgt2->y));
        $this->assertEquals(90,round($tgt2->h));
        
        
    }
    
    public function testOgz()
    {
        $knp = new Knp('КНП-5', new Grc(10000, 20000, 100));
        $tgt = new Point('пехота', new Grc(10707,20707,110));
        $ctrl = new Polar(new Directional(7, 50),1000,new Sdu('+',0,10));
        $res = Topo::ogz($tgt,$knp);
        $this->assertTrue($res instanceof Polar);
        $this->assertEquals((string) $ctrl, (string) $res);
    }
}
