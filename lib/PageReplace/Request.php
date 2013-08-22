<?php namespace PageReplace;

class Request {

  public function __construct() {
  }

  public function checkSecurity() {
    if( !$this->getNonce() || !wp_verify_nonce($this->getNonce(), 'pagereplace_nonce')) {
      return false;
    }
    return true;
  }

  private function getNonce() {
    return $_GET['pagereplace_nonce'];
  }

  private function getRequestType() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $contents = file_get_contents('php://input');
      return json_decode($contents, true);

    } else {
      return $_GET;
    }
  }

  public function getRequest($key) {
    $request = $this->getRequestType();
    if (isset($request[$key])) {
      return $request[$key];
    }
    return false;
  }

}

?>
