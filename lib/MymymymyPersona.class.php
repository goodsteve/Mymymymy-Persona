<?php
/**
 * @class MymymymyPersonaServer
 * @description The Mymymymy Persona! PHP server class.
 * @returns null
 * @usage
 * $mps = new MymymymyPersonaServer($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT']);
 * $mps->run();
 * $mps->close();
 */
class MymymymyPersonaServer {
  private $assertion            = '';
  private $audience             = '';
  private $authenticated        = false;
  private $command              = '';
  private $personaResponse      = null;
  private $personaVerifyStatus  = 'okay';
  private $personaVerifyUrl     = 'https://verifier.login.persona.org/verify';
  private $protocol             = 'https://';
  private $requestPacket        = array();
  private $requiredFields       = array('login' => array('assertion'));
  private $serverName           = '';
  private $serverPort           = '';
  private $validCommands        = array('login', 'logout');
  /**
  * @function login
  * @description Verifies the Mozilla Persona assertion specified by the client (javascript).
  * @returns null
  *
  * The Mymymymy Persona! javascript client sends an HTML post that includes the Persona assertion.
  *
  * This function can be extended to include calls to a database or cache.  Use the verified
  * Mozilla Persona email as your database table's primary key, or at the very least remember to
  * put a unique constraint on the verified email field.  It will save you some debugging later on ;)
  *
  * You may also want to include function calls to update a sessions table / cache.  Again, use
  * the verified Persona email address as your table record identifier.
  *
  * The last things to mention are COOKIES and SESSIONS.  You may want to use custom
  * cookies and sessions in your web application.  If so, you'll probably want to call your
  * cookie/session init functions after setting $this->authenticated = true; but before
  * calling $this->response();
  */
  private function login() {
    $this->assertion  = $this->requestPacket['assertion'];
    $postdata = 'assertion=' . urlencode($this->assertion) . '&audience=' . urlencode($this->audience);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->personaVerifyUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    $res = json_decode(curl_exec($ch));
    curl_close($ch);
    if ($res->status === $this->personaVerifyStatus) {
      // Persona assertion verified.
      // Respond by calling the mpc.success() client function.
      // Send the Persona response object to the mpc.success() client function.
      $this->personaResponse  = $res;
      $this->authenticated    = true;
      
      /**
       * Application Code
       */
      
      // If you want to talk to a database,
      // talk to a cache pool,
      // or set cookies / sessions,
      // put that code here.
      
      /**
       * End Application Code
       */
      
      $this->response('success', get_object_vars($res));
    } else {
      // Failed to verify assertion.
      // Respond by calling the mpc.failure() client function.
      // Send a failure message to the mpc.failure client function.
      $this->response('failure', get_object_vars($res));
    }
    return null;
  }
  
  /**
  * @function logout
  * @description Logout.
  * @returns null
  *
  * Send a response to the client telling mpc to display the Sign In button.
  */
  private function logout() {
    $this->assertion        = '';
    $this->personaResponse  = null;
    $this->authenticated    = false;
    
    /**
    * Application Code
    */
    
    // If you want to talk to a database,
    // talk to a cache pool,
    // or unset cookies / sessions,
    // put that code here.
    
    /**
    * End Application Code
    */
    
    $this->response('showSigninButton');
    return null;
  }
  
  /**
  * @function response
  * @description Send a formatted JSON response packet to the Mymymymy Persona! javascript client object (mpc).
  * @returns null
  *
  * The mpc.request() client object function typically processes all mps server responses.
  */
  private function response($mpcCommand = '', $responseData = array()) {
    $responsePacket = array('mpcCommand' => $mpcCommand, 'responseData' => $responseData);
    echo json_encode($responsePacket);
    return null;
  }
  
  /**
  * @function showRequiredArguments
  * @description Show required HTML form post fields.
  * @returns null
  */
  private function showRequiredArguments() {
    echo '<br /><br />Required command arguments: ' . implode(', ', $this->requiredFields[$this->command]) . '.';
    return null;
  }
  
  /**
  * @function showValidCommands
  * @description Show valid server commands.
  * @returns null
  */
  private function showValidCommands() {
    echo '<br /><br />Valid server commands: ' . implode(', ', $this->validCommands) . '.';
    return null;
  }
  
  /**
  * @function validateRequest
  * @description Validate HTML form post values.
  * @returns true | false
  */
  private function validateRequest() {
    // Does the server command have any required fields?
    if (isset($this->requiredFields[$this->command])) {
      // Loop through and verify each required field.
      foreach ($this->requiredFields[$this->command] AS $key => $fieldName) {
        if (!isset($this->requestPacket[$fieldName]) || !$this->requestPacket[$fieldName]) {
          // HTML form post is missing a required field.
          return false;
        }
      }
    }
    // The client request has been validated.
    return true;
  }
  

  
  /**
  * @function __contstruct
  * @description Set the Mymymymy Persona! server object's audience information.
  * @returns null
  */
  public function __construct($serverName = '', $serverPort = '') {
    $this->serverName = $serverName;
    $this->serverPort = $serverPort;
    if ($serverPort == 80) {
      $this->protocol = 'http://';
    }
    $this->audience   = $this->protocol . $this->serverName . ':' . $this->serverPort;
    return null;
  }
  
  /**
  * @function close
  * @description Close server connections.
  * @returns null
  *
  * If you have anything you want to do at the end of your script,
  * include it in this function.
  */
  public function close() {
    
    /**
    * Application Code
    */
    
    // If you want to do anything else
    // at the end of your script,
    // you can put that code here.
    
    /**
    * End Application Code
    */
    
    return null;
  }
  
  /**
  * @class MymymymyPersonaServer
  * @function getPersonaData
  * @description Returns the current user Persona data.
  * @returns null | Persona resonse object
  */
  public function getPersonaData() {
    return $this->personaResponse;
  }
  
  /**
  * @class MymymymyPersonaServer
  * @function isAuthenticated
  * @description Returns the current authentication status.
  * @returns true | false
  */
  public function isAuthenticated() {
    return $this->authenticated;
  }
  
  /**
  * @class MymymymyPersonaServer
  * @function run
  * @description Run the Mymymymy Persona! server.
  * @returns null
  *
  * The Mymymymy Persona! server objects acts on the:
  *
  * $_POST['mpsCommand']
  *
  * client form post.
  *
  * If the client posted a valid mpsCommand, then the mps
  * object will process the command and send a formatted
  * JSON response packet back to the client object (mpc).
  */
  public function run() {
    // The client posted an mpsCommand
    if (isset($_POST['mpsCommand'])) {
      $this->command        = $_POST['mpsCommand'];
      $this->requestPacket  = $_POST;
      // Check to see if the command is valid.
      if (in_array($this->command , $this->validCommands)) {
        // Validate the HTML form post values.
        if ($this->validateRequest()) {
          // Run the server command.
          $this->$_POST['mpsCommand']();
        } else {
          // Missing required field(s).
          echo 'Missing required arguments for server command: `' . $this->command  . '`.';
          $this->showRequiredArguments();
        }
      } else {
        // Invalid server command.
        echo 'Invalid server command: `' . $this->command  . '`.';
        $this->showValidCommands();
      }
    } else {
      // No server command specified.
      echo 'Please specify a server command.';
      $this->showValidCommands();
    }
    return null;
  }
}
?>