<?php

/**
 * Ashkandari Loader
 * This file pulls in all the neccessary files which contains:
 * - Configuration
 * - Classes
 * - Functions
	 */

// Config files
require_once('config/app.cfg.php');
require_once('config/db.cfg.php');
//require_once('config/debug.cfg.php');

/**
 * Classes
 * - Website Generic
 * - Battle.net Generic
 * - Database Management
 * - Primary Professions
 * - Secondary Professions
 * - Forums
 * - News Items
 */

// Website Generic
require_once(CLASSES.'/Account.php');
require_once(CLASSES.'/Teamspeak.php');
require_once(CLASSES.'/Page.php');
require_once(CLASSES.'/Email.php');

// Battle.net Generic
require_once(CLASSES.'/Application.php');
require_once(CLASSES.'/Character.php');
require_once(CLASSES.'/Class.php');
require_once(CLASSES.'/Race.php');
require_once(CLASSES.'/Rank.php');
require_once(CLASSES.'/Realm.php');
require_once(CLASSES.'/Spec.php');
require_once(CLASSES.'/Team.php');

// Database Management
require_once(CLASSES.'/DBQuery.php');

// Primary Professions
require_once(CLASSES.'/Professions/Profession.php');
require_once(CLASSES.'/Professions/Alchemy.php');
require_once(CLASSES.'/Professions/Blacksmithing.php');
require_once(CLASSES.'/Professions/Enchanting.php');
require_once(CLASSES.'/Professions/Engineering.php');
require_once(CLASSES.'/Professions/Herbalism.php');
require_once(CLASSES.'/Professions/Inscription.php');
require_once(CLASSES.'/Professions/Jewelcrafting.php');
require_once(CLASSES.'/Professions/Leatherworking.php');
require_once(CLASSES.'/Professions/Mining.php');
require_once(CLASSES.'/Professions/Skinning.php');
require_once(CLASSES.'/Professions/Tailoring.php');

// Secondary Professions
require_once(CLASSES.'/Professions/Cooking.php');
require_once(CLASSES.'/Professions/FirstAid.php');
require_once(CLASSES.'/Professions/Fishing.php');
require_once(CLASSES.'/Professions/Archaeology.php');

// Forums
require_once(CLASSES.'/Forum/Forum.php');
require_once(CLASSES.'/Forum/Board.php');
require_once(CLASSES.'/Forum/Thread.php');
require_once(CLASSES.'/Forum/Post.php');

// Wiki
require_once(CLASSES.'/Wiki/Wiki.php');
require_once(CLASSES.'/Wiki/Article.php');
require_once(CLASSES.'/Wiki/Discussion.php');

// News Items
require_once(CLASSES.'/News/Comment.php');
require_once(CLASSES.'/News/Item.php');

/**
 * Functions
 * - Encrypt
 * - Decrypt
 */
require_once(FUNCTIONS.'/encrypt.php');
require_once(FUNCTIONS.'/decrypt.php');