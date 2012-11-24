var mpc = null; // I be a global variable, dawg.  mpc is short for [M]ymymymy[P]ersona[C]lient.

/**
 * @class MymymymyPersonaClient
 * @function MymymymyPersonaClient
 * @description The Mymymymy Persona! javascript client class.
 * @returns null
 * @usage
 * mpc = new MymymymyPersonaClient();
 * mpc.run();
 */
function MymymymyPersonaClient() {
  // HTML id's
  this.personaBarId     = 'personaBar';
  this.signinButtonId   = 'signin';
  this.signoutButtonId  = 'signout';
  // Server URL
  this.serverUrl        = 'server.php';
  // DOM objects
  this.signinButton     = null;
  this.signoutButton    = null;
  // User object
  this.user             = {};
  this.user.audience    = '';
  this.user.email       = '';
  this.user.expires     = '';
  this.user.issuer      = '';
  this.user.status      = '';
  return null;
}

/**
 * @function failure
 * @description Server failed to authenticate user.
 * @returns null
 *
 * The server failed to verify the Mozilla Persona assertion.
 * Sets this.personaBarId's innerHTML to the Sign In button.
 * Append the server response message to this.personaBarId's innerHTML.
 */
MymymymyPersonaClient.prototype.failure = function(responsePacket) {
  mpc.showSigninButton();
  console.log(responsePacket);
  if (typeof(responsePacket['responseData']) !== 'undefined' && responsePacket['responseData'] != null) {
    $('#' + mpc.personaBarId).append(' ' + responsePacket['responseData'].reason);
  }
  return null;
}

/**
 * @function login
 * @description 
 * @returns null
 *
 * Display the cool spinny gif.
 * Create an AJAX request packet.
 * Send the AJAX login request.
 */
MymymymyPersonaClient.prototype.login = function(assertion) {
  var requestPacket = {'mpsCommand':'login', 'assertion':assertion};
  mpc.showAuthenticatingStatus();
  mpc.request(requestPacket);
  return null;
}

/**
 * @function logout
 * @description 
 * @returns null
 *
 * Create an AJAX request packet.
 * Send the AJAX logout request.
 */
MymymymyPersonaClient.prototype.logout = function() {
  var requestPacket = {'mpsCommand':'logout'};
  mpc.request(requestPacket);
  return null;
}

/**
 * @function request
 * @description Sends an AJAX POST request to this.serverUrl.
 * @returns null
 *
 * Sends a formatted request to this.serverUrl.
 * Processes the server's JSON response packet.
 * Calls mpc function (mpcCommand) if prompted by server.
 */
MymymymyPersonaClient.prototype.request = function(packet) {
  var responsePacket;
  $.post(this.serverUrl, packet, function(responsePacket) {
    if (responsePacket['mpcCommand'] !== 'undefined' && responsePacket['mpcCommand'] != "") {
      if (typeof(mpc[responsePacket['mpcCommand']]) == 'function') {
        mpc[responsePacket['mpcCommand']](responsePacket);
      }
    }
  }, 'json');
  return null;
}

/**
 * @function run
 * @description Run Mymymymy Persona!
 * @returns null
 *
 * The run() result will update the innerHTML of div id this.personaBarId.
 * If the user authenticates then a Sign Out button will be displayed.
 * If the user is not authenticated a Sign In button will be displayed.
 */
MymymymyPersonaClient.prototype.run = function() {
  navigator.id.watch({
    loggedInUser: mpc.user.email,
    onlogin: function(assertion) {
      mpc.login(assertion);
    },
    onlogout: function() {
      mpc.logout();
    }
  });
  mpc.showSigninButton();
  return null;
}

/**
 * @function setUserVars
 * @description 
 * @returns null
 *
 * Set client object user variables equal to Persona user object server response.
 */
MymymymyPersonaClient.prototype.setUserVars = function(responseData) {
  this.user.audience    = responseData.audience;
  this.user.email       = responseData.email;
  this.user.expires     = responseData.expires;
  this.user.issuer      = responseData.issuer;
  this.user.status      = responseData.status;
  return null;
}


/**
 * @function showAuthenticatingStatus
 * @description Show the cool spinny gif.
 * @returns null
 *
 * Sets this.personaBarId's innerHTML to a cool spinny gif before the AJAX request is fired off.
 */
MymymymyPersonaClient.prototype.showAuthenticatingStatus = function() {
  $('#' + this.personaBarId).html('<img src="img/loading_16.gif" border="0" /> Authenticating...');
  return null;
}

/**
 * @function showSigninButton
 * @description Show the signin button.
 * @returns null
 *
 * User is not authenticated, show the Sign In button and bind the onclick event.
 */
MymymymyPersonaClient.prototype.showSigninButton = function() {
  $('#' + this.personaBarId).html('<button id="' + this.signinButtonId + '" title="Signin with Mozilla Persona">Sign In</button>');
  this.signinButton = document.getElementById(this.signinButtonId);
  this.signinButton.onclick = function() { navigator.id.request(); };
  return null;
}

/**
 * @function showSignoutButton
 * @description Show the signout button.
 * @returns null
 *
 * User is authenticated, show the Sign Out button and bind the onclick event.
 * Show the active user's email address next to the Sign Out button.
 */
MymymymyPersonaClient.prototype.showSignoutButton = function() {
  $('#' + this.personaBarId).html('Signed in as: ' + mpc.user.email + '.  <button id="' + this.signoutButtonId + '">Sign Out</button>');
  this.signoutButton = document.getElementById(this.signoutButtonId);
  this.signoutButton.onclick = function() { navigator.id.logout(); };
  return null;
}

/**
 * @function success
 * @description User authenticated successfully.
 * @returns null
 *
 * The server was able to verify the Mozilla Persona assertion.
 * Sets this.personaBarId's innerHTML to the Sign Out button.
 */
MymymymyPersonaClient.prototype.success = function(responsePacket) {
  if (typeof(responsePacket['responseData']) !== 'undefined' && typeof(responsePacket['responseData']) == 'object') {
    mpc.setUserVars(responsePacket['responseData']);
    mpc.showSignoutButton();
  }
  return null;
}
