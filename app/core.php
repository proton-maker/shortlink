<?php
/**
 * =======================================================================================
 *                           GemFramework (c) Xsantana                                     
 * ---------------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework as such distribution
 *  or modification of this framework is not allowed before prior consent from
 *  Xsantana. If you find that this framework is packaged in a software not distributed 
 *  by Xsantana or authorized parties, you must not use this software and contact Xsantana
 *  at https://piliruma.co.id/contact to inform them of this misuse.
 * =======================================================================================
 *
 * @package Xsantana\Premium-URL-Shortener
 * @author Xsantana (https://piliruma.co.id) 
 * @license https://piliruma.co.id/licenses
 * @link https://piliruma.co.id  
 */


  // Framework Version
  define("_VERSION","1.1");
	
  // InApp Safety
  define("_INAPP", TRUE);
  define("_STATE", "PROD");

  // Path Constants
  define("ROOT", dirname(dirname(__FILE__)));
  define("APP", ROOT.'/app');
  define("PUB", ROOT."/public");
  define("CORE", ROOT."/core");

  define("CONTROLLER", APP."/controllers");
  define("MODELS", APP."/models");
  define("MIDDLEWARE", APP."/middleware");
  define("LIBRARY", ROOT."/vendor");
  define("UPLOADS", PUB."/content");
  define("STORAGE", ROOT."/storage");
  define("LOGS", STORAGE."/logs");
  define("LOCALE", STORAGE."/languages");
  define("PLUGIN", STORAGE."/plugins");


  include(CORE."/functions/core.php"); 

  include(CORE."/GemError.class.php");

  include(LIBRARY."/autoload.php");  

  include(CORE."/Gem.class.php");

  Gem::preload();  

  include(ROOT."/config.php");

  include(APP."/routes.php");
