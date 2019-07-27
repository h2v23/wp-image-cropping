<?php

function wpic_plugin_activation() {
  add_option( 'wpic_config', $GLOBALS['wpic_config_default'], '', 'yes' );
}

function wpic_plugin_deactivation() {
  delete_option('wpic_config');
}

function wpic_admin_url($trail = '') {
  return admin_url('options-general.php?page=wp-image-cropping'.$trail);
}


function wpic_notification() {
  if (isset($_GET['_update'])&&(isset($_GET['page']))) {

    $type = (int) (isset($_GET['_update'])) ? $_GET['_update'] : 0 ;

    $class = '';
    $mess = '';

    switch ($type) {
      case 1:
      $mess = __('Settings saved', 'wpic');
      $class = 'updated';
      break;

      default:
      $mess = __('Something wrong', 'wpic');
      $class = 'error';
      break;
    }

    printf('<div class="%2$s settings-error notice is-dismissible"> <p><strong>%1$s.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button></div>',
      $mess,
      $class
    );
  }
}

function wpic_get($config_name = '') {
  static $config;
  if($config==null) {
    $config = get_option('wpic_config', false);
    !$config&&$config=$GLOBALS['wpic_config_default'];
  }
  return $config_name ? $config[$config_name] : $config;
}

/**
 * Pattern like /image-300x200.png
 * @return mixed
 */
function wpic_parse_image($image_url)  {
  $type = explode('.', $image_url);
  $type = end($type);
  $type = strtolower($type);

  $link = substr($image_url, 0, strlen($image_url) - strlen($type) - 1);
  preg_match("/\d+x\d+$/i", $link, $match);
  $dimention = explode('x', $match[0]);
  if(count($dimention)!==2)
    return false;
  $real_path = str_replace('-'.$match[0], '', $image_url);
  return [
    'dimension' => $match[0],
    'width' => $dimention[0],
    'height' => $dimention[1],
    'real_path' => $real_path,
    'image_url' => $image_url
  ];
}

function wpic_current_slug() {
  global $wp;
  return $wp->request;
}

function wpic_upload_dir() {
  return wp_get_upload_dir()['basedir'];
}

function wpic_cache_file($file_name = '') {
  return wpic_upload_dir() . "/wpic_cache/" . ($file_name ? md5($file_name) : '');
}

function wpic_cache_allowed() {
  $cache_path = wpic_cache_file();
  if (!file_exists($cache_path)) {
    return (mkdir($cache_path, 0777)) ? true : false;
  }
  return true;
}

function wpic_current_url_with_cache_file_existed() {
  $image_slug = wpic_current_slug();
  if (wpic_is_image($image_slug)) {
    $cache_file = wpic_cache_file($image_slug);
    if (file_exists($cache_file)) {
      wpic_display_default();
    } elseif (wpic_cache_allowed()) {
      return true;
    }
  }
  return false;
}

function wpic_display_default() {
  $default_path = wpic_cache_file('default');
  echo 'ahihi';
  echo $default_path;die;
  header('Content-Type: image/png');
  echo file_get_contents($default_path);
  die;
}

function wpic_is_image($image_url) {
  $type = explode('.', $image_url);
  if (count($type)<2) return false;

  $type = end($type);
  $type = strtolower($type);
  if (!in_array($type, array('jpg', 'png', 'jpeg', 'gif'))) {
    return false;
  }
  return true;
}