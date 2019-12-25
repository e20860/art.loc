<?php
    use yii\widgets\ListView;
    use yii\bootstrap\Modal;
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\widgets\Pjax;
    // $this     - view
    // $provider - ArrayDataProvider
    // $notch Notch
    $knp = Yii::$app->session['knp'];
    $tgtTypes = Yii::$app->session['tgtTypes'];
    // получает строки для текущей запрошенной страницы
    $rows = $provider->getModels();
?>
<div class="journal">
    <div class="container">
    <?php
    Modal::begin([
        'header' => '<h2>Результаты засечки цели</h2>',
        'toggleButton' => [
            'label' => 'Добавить результаты засечки',
            'tag' => 'button',
            'class' => 'btn btn-primary',
        ],
        'footer' => 'Низ окна',
    ]);
    ?> 
        <!--  Модальная форма -->
        <?php $form = ActiveForm::begin([
            'id' => 'notch-form',
            'action' => '/reconnaissance/notch',
            'method' => 'post',
            ]    
                ); ?>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($notch, 'num')->textInput(['autofocus' => true]) ?>
            </div>
            <div class="col-sm-8">
                <?php
                 $params = [
                    'prompt' => 'Выберите характер цели',
                    'class'=>'form-control text-left', 
                ];
                echo $form->field($notch, 'tgtType')->dropDownList($tgtTypes,$params);
                        //textInput(array('placeholder' => 'Пехота укрытая', 'class'=>'form-control text-left')) 
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($notch, 'hours') ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($notch, 'mins') ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($notch, 'accuracy') ?>
            </div>
            
        </div>
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($notch, 'alpha')->textInput(array('placeholder' => '00-00', 'class'=>'form-control text-left')) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($notch, 'range')->textInput(array('placeholder' => 'в метрах', 'class'=>'form-control text-left')) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($notch, 'height')->textInput(array('placeholder' => 'в метрах', 'class'=>'form-control text-left')) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($notch, 'e')->textInput(array('placeholder' => '(+/-)0-00', 'class'=>'form-control text-left')) ?>
            </div>
        </div>
        
        <?= $form->field($notch, 'notes')->textarea(['rows' => 2]) ?>

        <div class="form-group">
            <?= Html::submitButton('Записать', ['class' => 'btn btn-primary','name' => 'notch-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>  
       
    <?php Modal::end(); ?>    
        <hr>        
        <p class="h2 text-primary text-center">
            ЖУРНАЛ<br>разведки и обслуживания стрельбы<br>
            <?=$knp->unit?> батареи на <?=date('d.m.Y')?>
        <hr>
        </p>
        <div class="row">
            <div class="col-sm-6">
                Основное направление <?= Yii::$app->session['osn']?><br>
                Способ ориентирования приборов________</div>
            <div class="col-sm-6"><?= $knp?><br>
            Боковой НП (правый): X =_________, Y =_________
            </div>
        </div>
       <hr>
       <!-- шапка таблицы -->
       <div class="row">
           <div class="col-sm-1">
               Номер цели
           </div>
           <div class="col-sm-1">
               Время обн.
           </div>
           <div class="col-sm-2">
               Положение цели
           </div>
           <div class="col-sm-1">
               Правый
           </div>
           <div class="col-sm-1">
               Дальность
           </div>
           <div class="col-sm-1">
               Наименование цели
           </div>
           <div class="col-sm-2">
               Координаты цели
           </div>
           <div class="col-sm-1">
               Точность
           </div>
           <div class="col-sm-2">
               Примечания
           </div>
       </div>
       <hr>
        <?php
        Pjax::begin();
            echo ListView::widget([
                'dataProvider' => $provider,
                'itemView' => '_item',
                ]); 
        Pjax::end();
        ?>
       <div class="text-left">
            <a href="/reconnaissance/planshet" class="btn btn-info">Посмотреть крупномасштабный  планшет</a>
        </div>               
    </div>

</div>