<?php
namespace models;

use app\components\MeteoBulletin;
use app\components\Meteopost;
use app\components\MeteopostDMK;

class MeteoBulletinTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    // tests
    public function testFunctionality()
    {
	$this->assertTrue(true);
    }
	// test creation Bulletin
    public function testCreateBulletin()
    {
        $ddhhm = mktime(15,10,0,9,24,2019);
        $bulType = 2;
        $numAMS = '';
        $hAMS = 120;
        $windAMS = 5;
        $atmPressAMS = 15;
        $groups = [
            '02' => '010203',
            '04' => '040506',
        ];
	$mb = new MeteoBulletin($ddhhm, $bulType, $numAMS, $hAMS, $windAMS, $atmPressAMS, $groups);
        $this->assertTrue($mb instanceof MeteoBulletin);
        $strMb = 'Метео11приближённый-24151-0120-01505-02-010203-04-040506';
        $this->assertEquals($strMb,(string) $mb);
        $this->assertEquals('040506',$mb->getGroup('04'));
        $this->assertEquals(120,$mb->hAMS);
        $this->assertEquals($groups,$mb->groups);
    }
    
    public function testCompilerBulletinDMK()
    {
        $mc = new MeteopostDMK();
        $time = mktime(13,30,0,11,18,2019);
        $temp = -1;
        $hAMS = 90;
        $press = 759;
        $aW = 17;
        $sW = 5;
        $bul = $mc->compileBulletin(compact('temp', 'hAMS', 'press', 'aW', 'sW', 'time'));
        $contr = "Метео11приближённый-18133-0090-00967-02-661808-04-651910-08-642010-12-632011-16-622111-20-622111-24-622112-30-602212-40-602212";
        $this->assertEquals($contr, (string) $bul);
        $this->assertEquals(-17,$bul->airTempAMS);
        $mc->measure($temp, $hAMS, $press, $aW, $sW, $time);
        $bul1 = $mc->getBulletin();
        $this->assertEquals($contr, (string) $bul1);
    }
    
    public function testRecieverBulletin()
    {
        $contr = "Метео11приближённый-18133-0090-00967-02-661808-04-651910-08-642010-12-632011-16-622111-20-622111-24-622112-30-602212-40-602212";
        $rb = new MeteopostDMK();
        $rb->recieve($contr);
        $bul = $rb->getBulletin();
        $this->assertEquals($contr, (string) $bul);
        $contr1 = "Метео1105-18133-0090-00967-02-661808-04-651910-08-642010-12-632011-16-622111-20-622111-24-622112-30-602212-40-602212";
        $rb->recieve($contr1);
        $bul1 = $rb->getBulletin();
        $this->assertEquals($contr1, (string) $bul1);
    }
}