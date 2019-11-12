<?php
use yii\helpers\Html;
$this->title = 'Метеоподготовка стрельбы';
$bul = "Метео11приближённый-18133-0090-00967-02-661808-04-651910-08-642010-12-632011-16-622111-20-622111-24-622112-30-602212-40-602212";
?>
<div class="bulletin">
    <main role="main" class="container">
        <div class="row">
            <div class="text-center">
                <h1><?=Html::encode($this->title);?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <h3>Данные метеоизмерений</h3>
                <hr>
                <br>
                <form id="form1">
                    <div class="form-group row">
                        <label for="inputDate" class="col-sm-4 col-form-label">Дата и время</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" name="ddhhm" class="form-control" id="inputDate" placeholder="Дата измерений">
                        </div>
                    </div>
                    <fieldset class="form-group">
                        <div class="row">
                            <legend class="col-form-label col-sm-4">Средство</legend>
                            <div class="col-sm-8">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tool" id="gridRadios1" value="dmk" checked>
                                    <label class="form-check-label" for="gridRadios1">
                                        ДМК
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tool" id="gridRadios2" value="vr2">
                                    <label class="form-check-label" for="gridRadios2">
                                        ВР-2
                                    </label>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-group row">
                        <label for="inputHAMS" class="col-sm-4 col-form-label">Высота метеопоста</label>
                        <div class="col-sm-8">
                            <input type="text" name="hAMS" class="form-control" id="inputHAMS" placeholder="метров над уровнем моря">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPress" class="col-sm-4 col-form-label">Наземное давление</label>
                        <div class="col-sm-8">
                            <input type="text" name="press" class="form-control" id="inputPress" placeholder="миллиметров рт.ст.">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="inputTemp" class="col-sm-4 col-form-label">Наземная температура</label>
                        <div class="col-sm-8">
                            <input type="text" name="temp" class="form-control" id="inputTemp" placeholder="градусов Цельсия">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputAW" class="col-sm-4 col-form-label">Направление ветра</label>
                        <div class="col-sm-8">
                            <input type="text" name="aW" class="form-control" id="inputAW" placeholder="больших делений угломера">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputW" class="col-sm-4 col-form-label">Скорость ветра</label>
                        <div class="col-sm-8">
                            <input type="text" name="sW" class="form-control" id="inputW" placeholder="метров в секунду">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-10">
                            <button type="submit" id="measure" class="btn btn-primary">Отправить</button>
                        </div>
                    </div>                    
                </form>
            </div>
            <div class="col-sm-6">
                <h3>Текущий бюллетень</h3>
                <hr>
                <div class="meteobulletin" id="bul">
                    <p>
                        <?=Html::encode($bul);?>
                    </p>
                </div>
            </div>
        </div>
    </main>
</div>
<?php
    $js = <<<JS
        $('#measure').on('click', function() {
        var form = $('#form1');
        var data = form.serialize();
        $.ajax({
            url:'measure',
            type:'POST',
            data: data,
            success: function(res){
                console.log(res);
                $("#bul").html(res);
            },
            error: function(){
                alert('Ошибка!!!');
            }
        });
        return false;
    });
JS;
    $this->registerJS($js);
?>