Mymymymy-Persona!
=================

An AJAX authentication system built around Mozilla Persona.

Mymymymy Persona! was designed to easily drop-in to an existing web application.


Client code
===========

Add the following script sources to your site's <head> tag:

<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script src="https://login.persona.org/include.js"></script>
<script type="text/javascript" src="js/MymymymyPersona.class.js"></script>

Omit the jquery-1.7.2.min.js include if your site already uses jQuery.


Add the next block of code to jQuery's $(function(){ // Put client code here. }); ready event to instantiate and run the MymymymyPersonaClient (mpc) client object:

mpc = new MymymymyPersonaClient();
mpc.run();



Server code
===========

Include the server class in your web application:

include_once('lib/MymymymyPersona.class.php');


Instantiate and run the MymymymyPersonaServer ($mps) server object:

$mps  = new MymymymyPersonaServer($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT']);
$mps->run();


You can optionally call the server object's close() function if you'd like to run any code at the end of your script:

$mps->close();



External resources
==================

For more information, please visit:

http://www.goodsteve.co/Mymymymy-Persona/index.html.
https://developer.mozilla.org/en-US/docs/Persona/Why_Persona
https://developer.mozilla.org/en-US/docs/Persona


Rgds,
Steve
