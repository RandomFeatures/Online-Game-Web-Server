<?php
/*
Description:    Start a new session
                
****************History************************************
Date:         	10.11.2009
Author:       	Allen Halsted
Mod:          	Creation
***********************************************************
*/

/* start the session */

if (session_id() == "") 
{
	ini_set("session.gc_maxlifetime", "3600"); 
	session_start(); // if no active session we start a new one
}

header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
?>