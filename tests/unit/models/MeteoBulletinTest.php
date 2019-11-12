<?php
namespace models;

use app\components\MeteoBulletin;
use app\components\MeteopostDMK;
use app\components\MeteopostVR2;

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
    
    public function testCorrectBulletin()    {
        $oldBul = "Метео1101-16011-0100-81258-0256-581704-0466-601806-0857-612008-1257-622311-1657-632512-2056-622812-2456-632911-3055-633112-4054-613315-5055-603515-6054-613714-8053-603914-1053-614317"; 
        $rcvd = "Метео1101-16011-0100-81258-02-581704-04-601806-08-612008-12-622311-16-632512-20-622812-24-632911-30-633112-40-613315-50-603515-60-613714-80-603914-10-614317";
        $amp = new MeteopostVR2();
        $amp->recieve($oldBul);
        $bul = $amp->getBulletin();
        $this->assertEquals($rcvd, (string) $bul);
        $this->assertEquals(1,$bul->bulType);
        $time = mktime(9,0,0,11,16,2019);
        $temp = 4.5;
        $hAMS = 110;
        $press = 743;
        $aW = 25;
        $sW = 80;
        $interval = round(($time - $bul->ddhhm)/3600);
        $this->assertEquals(8,$interval);
        $this->assertTrue($interval > 3 && $interval < 12 && $bul->bulType == 1);
        $this->assertEquals(-8, $bul->airTempAMS);
        $amp->measure($temp, $hAMS, $press, $aW, $sW, $time);
        $newBul = 'Метео11приближённый-16090-0110-50761-02-602507-04-612608-08-622708-12-622708-16-632809-20-622809-24-632911-30-633112-40-613315';
        $bul1 = $amp->getBulletin();
        $this->assertEquals($newBul,(string) $bul1);
    }
}