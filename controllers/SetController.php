<?php

namespace app\controllers;

use app\models\BattleOrder;
use app\components\Directional;
use app\components\Knp;
use app\components\Op;
use app\components\Grc;

/**
 * Установки системы
 *
 * @author Admin
 */
class SetController extends \yii\base\Controller {

    protected function SetOrder($bo)
    {
        $sess = \Yii::$app->session;
        $sess->open();
        if(isset($bo)){
            $_SESSION['osn'] = new Directional($bo['osn'], 0);
            $reg = '/(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/s';
            $matches = [];
            preg_match($reg, $bo['operDate'], $matches);
            $_SESSION['operDate'] = mktime($matches[4], $matches[5], 0, $matches[2],
                    $matches[3], $matches[1]);
            $_SESSION['unitName'] = $bo['unitName'];
            $_SESSION['knp'] = new Knp('кнп батареи', new Grc($bo['xknp'],$bo['yknp'],$bo['hknp']));
            $_SESSION['bnp'] = new Knp('правый', new Grc($bo['xbnp'],$bo['ybnp'],$bo['hbnp']));
            $_SESSION['op'] = new Op('op батареи', new Grc($bo['xop'],$bo['yop'],$bo['hop']));
            $_SESSION['op']->nGuns = $bo['nGuns'];
            $_SESSION['op']->caliber = $bo['caliber'];
            $_SESSION['op']->artSystem = $bo['artSystem'];
            $_SESSION['battleOrder'] = $bo;
        }
    }
    
    protected function fillModel()
    {
        $sess = \Yii::$app->session['battleOrder'];
        $bo = new BattleOrder();
        if(!isset($sess['osn'])){return $bo;}
        $bo->osn = $sess['osn'];//round($sess['osn']->rad/0.10471975511966);
        $bo->operDate = $sess['operDate'];
        $bo['unitName'] = $sess['unitName'];
        $bo['xknp'] = $sess['xknp'];
        $bo['yknp'] = $sess['yknp'];
        $bo['hknp'] = $sess['hknp'];
        $bo['xbnp'] = $sess['xbnp'];
        $bo['ybnp'] = $sess['ybnp'];
        $bo['hbnp'] = $sess['hbnp'];
        $bo['nGuns'] = $sess['nGuns'];
        $bo['caliber'] = $sess['caliber'];
        $bo['artSystem'] = $sess['artSystem'];
        $bo['xop'] = $sess['xop'];
        $bo['yop'] = $sess['yop'];
        $bo['hop'] = $sess['hop'];
        return $bo;
    }

    public function actionBattleorder()
    {
        $bo = \Yii::$app->request->post('BattleOrder');
        if(!is_null($bo)){
            $this->SetOrder($bo);
            return \Yii::$app->response->redirect('/reconnaissance/journal');
        } else {
            $model = $this->fillModel();
            return $this->render('battle-order',[
                'model' => $model,
            ]);
        }
    }

}
