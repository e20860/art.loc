<?php

/*
 * Slavko current project
 * No License (OPEN GNU)
 * Author E.Slavko <e20860@mail.ru>
 */

namespace app\components;

/**
 * Метеофабрика. Возвращает метеопост 
 * взависимости от средства измерения метеоэлементов
 *
 * @author E.Slavko <e20860@mail.ru>
 */
class MeteoFab {
    
    public function getPost($tool) {
        switch ($tool){
            case 'dmk':
                $post = new MeteopostDMK();
                break;
            case 'vr2':
                $post = new MeteopostVR2;
                break;
            default :
                $post = new MeteopostDMK();
        }
        return $post;
    }
}
