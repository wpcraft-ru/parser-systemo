<?php
/*
Plugin Name: Parser by Systemo
Version: 0.1
Plugin URI: ${TM_PLUGIN_BASE}
Description:
Author: Systemo
Author URI: ${TM_HOMEPAGE}
*/


class ParserSystemo {

  function __construct(){
    add_shortcode( 'parser_s', array($this, 'parser_s_cb'));
  }

  function parser_s_cb($attr){
    include_once 'inc/simplehtmldom/simple_html_dom.php';

    extract(shortcode_atts(array(
      'url'=> 'http://systemo.biz/blog/',
    ),$atts));

    $args = array(
    	'method'     => 'GET',
      'timeout'     => 5,
    	'redirection' => 5,
    	'httpversion' => '1.0',
    	'user-agent'  => 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' ),
    	'blocking'    => true,
    	'headers'     => array(),
    	'cookies'     => array(),
    	'body'        => null,
    	'compress'    => false,
    	'decompress'  => true,
    	'sslverify'   => true,
    	'stream'      => false,
    	'filename'    => null
    );

      $result = wp_safe_remote_request( $url, $args );

      $body = new simple_html_dom();

      $body->load($result['body']);

      $list = $body->find('#main .status-publish');

      ob_start();
      ?>
        <div>
          <h1>Ответ</h1>
          <?php var_dump($result['response']); ?>

          <h1>Результат</h1>
          <ul>
            <?php foreach($list as $element): ?>
                <li>
                  <?php
                    //получаем ИД атрибут элемента
                    $id = $element->id;
                    echo $id;
                  ?>
                  <br/>
                  <?php
                    //Находим внутри элемента заголовок с ссылкой и выдергиваем текст ссылки
                    $title = $element->find('h1.entry-title a', 0);
                    echo $title->plaintext;
                  ?>
                  <br/>
                  <?php
                    //Находим внутри элемента ссылку
                    $title = $element->find('h1.entry-title a', 0);
                    echo $title->href;
                  ?>
                  <hr>
                </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php
      $html = ob_get_contents();
      ob_get_clean();
      return $html;
  }


}
$TheParserSystemo = new ParserSystemo;