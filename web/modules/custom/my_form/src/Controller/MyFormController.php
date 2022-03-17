<?php

namespace Drupal\my_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;


/**
 * Defines HelloController class.
 */
class MyFormController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
 

  public function mostrartodo() {

    
   
    $dato = array();    

    $dato['d1'] = array(
        '#markup' => '<strong> Administración de datos personales </strong><br><br>',
    );


    $url = Url::fromRoute('my_form.form');
    $project_link = Link::fromTextAndUrl(t('Crear nuevo registro'), $url);
    $project_link = $project_link->toRenderable();
    $project_link['#attributes'] = array('class' => array('button','button--primary','button--small')); 

    $dato['d2'] = array(
        '#markup' => '<i> Para crear nuevos usaurios, pulse click en el siguente boton'. render($project_link).'</i><br><br>',
    );


    $rows = array();
    $rows = listar();
    ksm(listar());

    $dato['table'] = [
      '#rows' => $rows,
      '#header' => [t('Id'), t('Nombre'), t('Apellido'), t('Email'), t('Celular'), t('Fecha'), t('Ver'), t('Editar'), t('Eliminar')],
      '#type' => 'table',
      '#empty' => t('No content available.'),
    ];
    $dato['pager'] = [
      '#type' => 'pager',
      '#weight' => 10,
    ];


    return $dato;
  } 


}

function listar(){

    $database = \Drupal::database();
    $query = $database->select('datosPersonales', 'dp')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(5);
    $query->fields('dp');
    $result = $query
      ->execute();

    $rows = [];

    global $base_url;
    foreach ($result as $row) {
      dpm($row);

      $row = (array) $row;

      $url = Url::fromUri($base_url.'/form-example/'.$row['id']);
      $ver_link = Link::fromTextAndUrl(t('Ver'), $url)->toString();
      $row['ver'] = $ver_link;
      $row = (array) $row;

      $url = Url::fromUri($base_url.'/form-example/'.$row['id'].'/edit');
      $editar_link = Link::fromTextAndUrl(t('Editar'), $url)->toString();
      $row['editar'] = $editar_link;
      $row = (array) $row;

      $url = Url::fromUri($base_url.'/form-example/'.$row['id'].'/delete');
      $eliminar_link = Link::fromTextAndUrl(t('Eliminar'), $url)->toString();
      $row['eliminar'] = $eliminar_link;


      $rows[] = $row;     
    }

    return $rows;


}
