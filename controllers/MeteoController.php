<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\controllers;


use yii\web\Controller;
use app\components\MeteoFab;
use app\models\Measurements;
/**
 * Description of MeteoController
 *
 * @author Admin
 */
class MeteoController extends Controller
{
    public function actionBulletin()
    {
        return $this->render('bulletin');
    }
    
    public function actionMeteo()
    {
        $model = new Measurements();
        return $this->render('meteo', compact('model'));
    }
    
    public function actionRecieve()
    {
        if(\Yii::$app->request->isAjax){
            $b = \Yii::$app->request->post();
            $strBul = 'Метео11'.$b['numAMS'].'-'.$b['ddhhm'].'-'.$b['hAMS'].'-'.
                    $b['hhhtt'] . '-02-'.$b['g02']. '-04-'.$b['g04']. '-08-'.$b['g08']
                    . '-12-'.$b['g12']. '-16-'.$b['g16']. '-20-'.$b['g20']
                    . '-24-'.$b['g24']. '-30-'.$b['g30']. '-40-'.$b['g40'];
            $ams = new \app\components\MeteopostDMK();
            $ams->recieve($strBul);
            $bul = $ams->getBulletin();
            $session = \Yii::$app->session;
            $session['bulletin'] = $bul;
            return $this->renderAjax('result', compact('bul'));
        }
    }

    public function actionMeasure()
    {
        if(\Yii::$app->request->isAjax){
            $data = \Yii::$app->request->post()['Measurements'];
            $reg = '/(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/s';
            $matches = [];
            preg_match($reg, $data['ddhhm'], $matches);
            $time = mktime($matches[4],$matches[5],0,
                   $matches[2],$matches[3],$matches[1]);
            $fab = new MeteoFab();
            $ams = $fab->getPost($data['tool']);
            $ams->measure($data['temp'], $data['hAMS'], $data['press'],
                    $data['aW'], $data['sW'], $time);
            $bul = $ams->getBulletin();
            $session = \Yii::$app->session;
            $session['bulletin'] = $bul;
            return $this->renderAjax('result', compact('bul'));
        }
    }
}
