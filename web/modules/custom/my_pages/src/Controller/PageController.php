<?php

namespace Drupal\my_pages\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines HelloController class.
 */
class PageController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello, World!<br><br>'),
    ];
  }

  public function variosDatos() {
   
    $dato = array();

    $dato['d1'] = array(
        '#markup' => 'Hello, World!<br><br>',
    );

    $dato['d2'] = array(
        '#markup' => '<strong> Hello, World 2! </strong><br><br>',
    );
    $dato['d3'] = array(
        '#markup' => '<i> Hello, World 3! </i><br><br>',
    );

    return $dato;
  }

  public function dateForm() {
   
    $dato = array();

    $dato['d1'] = array(
        '#markup' => 'Hello, World!<br><br>',
    );

    $dato['d2'] = array(
        '#markup' => '<strong> Hello, World 2! </strong><br><br>',
    );
    $dato['d3'] = array(
        '#markup' => '<i> Hello, World 3! </i><br><br>',
    );

    $form = \Drupal::formBuilder()->getForm('\Drupal\my_form\Form\MyForm');

    $dato['d4'] = $form;


    return $dato;
  }


  public function template() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\my_form\Form\MyForm');
 
    return [
      '#theme' => 'my_template',
      '#my_variable' => $this->t('Esta es mi variable'),
      '#form' => $form,
    ];
 
  }







 


}
