<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$css = "
    #numAMS {width:50px;}
    #hAMS {width:60px;}
    input[type=text]{ width:90px; height:25px;}
";       
$this->registerCss($css, ["type" => "text/css"], "myStyles" ); 

$session = Yii::$app->session;
if($session->has('bulletin')){
   $bul = (string) $session['bulletin']; 
} else {
    $bul = 'На этом месте должен быть метеобюллетень';
}

?>
        
<div class="meteo">
    <div class="container">
        <p class="h1 text-center text-primary">Метеорологическая подготовка стрельбы</p>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <div class="row">
                    
                    <p class="h4 text-center text-info">Приём бюллетеня от АМС старшего начальника</p>
                    <hr>
                    <div class="row">
                        <form id="getBul">
                            <div class="form-row">
                                <div class="form-group">
                                <label for="numAMS" class="col-sm-2">Метео11-</label>
                                <input  class="form-control col-sm-2" type="text" name="numAMS" id="numAMS" placeholder="№№">
                                </div>
                                <div class="form-group">
                                <label for="ddhhm" class="col-sm-1">-</label>
                                <input  class="form-control col-sm-2" type="text" name="ddhhm" id="ddhhm" placeholder="ДДЧЧМ">
                                </div>
                                <div class="form-group">
                                <label for="hAMS" class="col-sm-1">-</label>
                                <input  class="form-control col-sm-2" type="text" name="hAMS" id="hAMS" placeholder="ВВВ">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                <label for="hhhtt" class="col-sm-1">-</label>
                                <input  class="form-control" type="text" name="hhhtt" id="hhhtt" placeholder="НННТТ">
                                </div>
                                <div class="form-group">
                                <label for="g02" class="col-sm-1">-02-</label>
                                <input  class="form-control col-sm-2" type="text" name="g02" id="g02" placeholder="ТТННСС">
                                </div>
                                <div class="form-group">
                                <label for="g04" class="col-sm-1">-04-</label>
                                <input  class="form-control col-sm-2" type="text" name="g04" id="g04" placeholder="ТТННСС">
                                </div>
                                <div class="form-group">
                                <label for="g08" class="col-sm-1">-08-</label>
                                <input  class="form-control col-sm-2" type="text" name="g08" id="g08" placeholder="ТТННСС">
                                </div>
                                <div class="form-group">
                                <label for="g12" class="col-sm-1">-12-</label>
                                <input  class="form-control col-sm-2" type="text" name="g12" id="g12" placeholder="ТТННСС">
                                </div>
                                <div class="form-group">
                                <label for="g16" class="col-sm-1">-16-</label>
                                <input  class="form-control col-sm-2" type="text" name="g16" id="g16" placeholder="ТТННСС">
                                </div>
                                <div class="form-group">
                                <label for="g20" class="col-sm-1">-20-</label>
                                <input  class="form-control col-sm-2" type="text" name="g20" id="g20" placeholder="ТТННСС">
                                </div>
                                <div class="form-group">
                                <label for="g24" class="col-sm-1">-24-</label>
                                <input  class="form-control col-sm-2" type="text" name="g24" id="g24" placeholder="ТТННСС">
                                </div>
                                <div class="form-group">
                                <label for="g30" class="col-sm-1">-30-</label>
                                <input  class="form-control col-sm-2" type="text" name="g30" id="g30" placeholder="ТТННСС">
                                </div>
                                <div class="form-group">
                                <label for="g40" class="col-sm-1">-40-</label>
                                <input  class="form-control col-sm-2" type="text" name="g40" id="g40" placeholder="ТТННСС">
                                </div>
                            </div>
                                <div class="form-row">
                                    <div class="col-sm-12">
                                        <hr>
                                    </div>
                                </div>
                            <div class="form-row">
                                
                                <div class="col-sm-12">
                                    <button class="btn btn-primary" id="sendBul">Принять</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <p class="h4 text-center text-info">Составление / исправление бюллетеня</p>
                <hr>
            <!--Активная форма -->
            <?php    
                $form = ActiveForm::begin([
                'id' => 'measure',
                'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                    'template' => '<div class="col-md-5">{label}</div><div class="col-md-3">{input}</div><div class="col-md-4">{error}</div>',
                    ],
                ]); ?>
                <?= $form->field($model, 'tool')
                         ->dropDownList(['dmk'=>'ДМК','vr2'=>'ВР-2']) ?>
                <?= $form->field($model, 'temp') ?>
                <?= $form->field($model, 'hAMS') ?>
                <?= $form->field($model, 'press') ?>
                <?= $form->field($model, 'aW') ?>
                <?= $form->field($model, 'sW') ?>
                <?= $form->field($model, 'ddhhm')->textInput(['type'=>'datetime-local','template' => '<div class="col-md-4">{label}</div><div class="col-md-6">{input}</div><div class="col-md-2">{error}</div>']
                        ) ?>

                <div class="form-group">
                    <div class="col-lg-offset-1 col-lg-11">
                        <?= Html::submitButton('Составить', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>                
            </div>
        </div>
        <hr>
        <div id="bul">
            <?=$bul?>
        </div>
        <hr>
    </div>
</div>
<?php
    $js = <<<JS
	$("#sendBul").on("click",function(evt){
		evt.preventDefault();
		var data = $("#getBul").serialize();
                console.log(data);
		$.ajax({
                    url:'/meteo/recieve',
                    type:'post',
                    data: data,
                    success:function(res){
                        $("#bul").html(res);
                    },
                    error:function(err){
			console.log(err);
                    }
		});
	});

        $('form').on('beforeSubmit', function(){
            var data = $(this).serialize();
            $.ajax({
            url: '/meteo/measure',
            type: 'POST',
            data: data,
            success: function(res){
            console.log(res);
            $("#bul").html(res);
            },
            error: function(jqXHR,err){
            alert(err);
            console.log(jqXHR);
            }
            });
            return false;
        });
JS;
    $this->registerJS($js);
?>