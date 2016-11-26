<?php
/*
Description:    Destroy the session and start a new one
                
****************History************************************
Date:         	08.26.2010
Author:       	Allen Halsted
Mod:          	Creation
***********************************************************
*/

/* start the session */
session_start(); // if no active session we start a new one

if(isset($_SESSION[captcha]))
	$capt = $_SESSION[captcha];

session_destroy();
unset($_SESSION);
ini_set("session.gc_maxlifetime", "3600"); 
session_start(); // if no active session we start a new one

$_SESSION[captcha] = $capt;

header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
?>