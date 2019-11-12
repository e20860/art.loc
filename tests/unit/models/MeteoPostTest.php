<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace models;

use app\components\MeteoFab;
use app\components\MeteopostDMK;
use app\components\MeteopostVR2;


/**
 * Description of MeteoPostTest
 *
 * @author Admin
 */
class MeteoPostTest extends \Codeception\Test\Unit
{
    public function testMeteoFab()
    {
        $tstFab = new MeteoFab();
        $postDefault = $tstFab->getPost('qq');
        $this->assertTrue($postDefault instanceof MeteopostDMK);
        $postVr2 = $tstFab->getPost('vr2');
        $this->assertTrue($postVr2 instanceof MeteopostVR2);
        $postDmk = $tstFab->getPost('dmk');
        $this->assertTrue($postDmk instanceof MeteopostDMK);
    }
}
