<?php
/**
 * Mymymymy Persona! Example
 * 
 * server.php needs to be placed in/under your document root, and must be callable by example.html.
 */

 
 
/**
 * Runtime configuration.
 *
 * Default settings are set for debugging.
 */
error_reporting(-1);
ini_set('display_errors', true);



/**
 * Include the Mymymymy Persona! PHP server class.
 *
 * You might want to host the Mymymymy Persona! server class file
 * outside of your document root.  That's perfectly fine, just remember
 * to update this include path to reflect your directory path changes.
 */
include_once('lib/MymymymyPersona.class.php');



/**
 * Instantiate and run the Mymymymy Persona! server class.
 *
 * Mozilla Persona verifying service requires a server name and port (or "audience")
 * to be sent along with the assertion.
 *
 * If you're running an Apache webserver then the $_SERVER approach should work just fine.
 *
 * What's nice is Persona even allows LAN IP server names (ie: 192.168.1.100).
 */
$mps  = new MymymymyPersonaServer($_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT']);
$mps->run();



/**
 * Don't invoke this method until the end of your program's execution.
 *
 * For example, in the following pseudocode example:
 *
 * if (open()) {
 *   main();
 * }
 * close();
 *
 * $mps->close(); should be added to the close() function.  In this case a:
 *
 * global $mps;
 *
 * statement would be required at the top of the close(); function.
 *
 * Anyway, the whole point of this stupid example is simply to show that you should
 * call $mps->close() at the end of your script, and not immediately following
 * $mps->run(); as it appears in this over-simlified example.
 * 
 */
$mps->close();
?>