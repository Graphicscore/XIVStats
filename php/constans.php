<?php

const ROLE_TANK = "tank";
const ROLE_HEAL = "healer";
const ROLE_DPS = "dps";
const ROLE_CRAFTER= "crafting";
const ROLE_GATHERER = "gathering";

const KEY = "KEY";
const TITLE = "TITLE";
const ROLE = "ROLE";
const COUNT = "COUNT";
const TYPE = "TYPE";
const CLASS_GLA = array(KEY => "level_gladiator", TITLE => "Gladiator", ROLE => ROLE_TANK);
const CLASS_PUG = array(KEY => "level_pugilist", TITLE => "Pugilist", ROLE => ROLE_DPS);
const CLASS_MRD = array(KEY => "level_marauder", TITLE => "Marauder", ROLE => ROLE_TANK);
const CLASS_LNC = array(KEY => "level_lancer", TITLE => "Lancer", ROLE => ROLE_DPS);
const CLASS_ARC = array(KEY => "level_archer", TITLE => "Archer", ROLE => ROLE_DPS);
const CLASS_ROG = array(KEY => "level_rogue", TITLE => "Rogue", ROLE => ROLE_DPS);
const CLASS_CNJ = array(KEY => "level_conjurer", TITLE => "Conjurer", ROLE => ROLE_HEAL);
const CLASS_THM = array(KEY => "level_thaumaturge", TITLE => "Thaumaturge", ROLE => ROLE_DPS);
const CLASS_ACN = array(KEY => "level_arcanist", TITLE => "Arcanist", ROLE => ROLE_DPS);
const CLASS_SCH = array(KEY => "level_scholar", TITLE => "Scholar", ROLE => ROLE_HEAL);
const CLASS_DRK = array(KEY => "level_darkknight", TITLE => "Dark Knight", ROLE => ROLE_TANK);
const CLASS_MCH = array(KEY => "level_machinist", TITLE => "Machinist", ROLE => ROLE_DPS);
const CLASS_AST = array(KEY => "level_astrologian", TITLE => "Astrologian", ROLE => ROLE_HEAL);
const CLASS_SAM = array(KEY => "level_samurai", TITLE => "Samurai", ROLE => ROLE_DPS);
const CLASS_RDM = array(KEY => "level_redmage", TITLE => "Red Mage", ROLE => ROLE_DPS);
const CLASS_BLU = array(KEY => "level_bluemage", TITLE => "Blue Mage", ROLE => ROLE_DPS);
const CLASS_GNB = array(KEY => "level_gunbreaker", TITLE => "Gunbreaker", ROLE => ROLE_TANK);
const CLASS_DNC = array(KEY => "level_dancer", TITLE => "Dancer", ROLE => ROLE_DPS);
const CLASS_CRP = array(KEY => "level_carpenter", TITLE => "Carpenter", ROLE => ROLE_CRAFTER);
const CLASS_BSM = array(KEY => "level_blacksmith", TITLE => "Blacksmith", ROLE => ROLE_CRAFTER);
const CLASS_ARM = array(KEY => "level_armorer", TITLE => "Armorer", ROLE => ROLE_CRAFTER);
const CLASS_GSM = array(KEY => "level_goldsmith", TITLE => "Goldsmith", ROLE => ROLE_CRAFTER);
const CLASS_LWR = array(KEY => "level_leatherworker", TITLE => "Leatherworker", ROLE => ROLE_CRAFTER);
const CLASS_WVR = array(KEY => "level_weaver", TITLE => "Weaver", ROLE => ROLE_CRAFTER);
const CLASS_ALC = array(KEY => "level_alchemist", TITLE => "Alchemist", ROLE => ROLE_CRAFTER);
const CLASS_CUL = array(KEY => "level_culinarian", TITLE => "Culinarian", ROLE => ROLE_CRAFTER);
const CLASS_MIN = array(KEY => "level_miner", TITLE => "Miner", ROLE => ROLE_GATHERER);
const CLASS_BTN = array(KEY => "level_botanist", TITLE => "Botanist", ROLE => ROLE_GATHERER);
const CLASS_FSH = array(KEY => "level_fisher", TITLE => "Fisher", ROLE => ROLE_GATHERER);

const GEAR_MAIN_HAND = array(KEY => 'main_hand', TITLE => "Main Hand");
const GEAR_OFF_HAND = array(KEY => 'off_hand', TITLE => "Off Hand");
const GEAR_HEAD = array(KEY => 'head', TITLE => 'Head');
const GEAR_BODY = array(KEY => 'body', TITLE => 'Body');
const GEAR_HANDS = array(KEY => 'hands', TITLE => 'Hands');
const GEAR_WAIST = array(KEY => 'waist', TITLE => 'Waist');
const GEAR_LEGS = array(KEY => 'legs', TITLE => 'Legs');
const GEAR_FEET = array(KEY => 'feet', TITLE => 'Feet');
const GEAR_EARS = array(KEY => 'ears', TITLE => 'Ears');
const GEAR_NECK = array(KEY => 'neck', TITLE => "Neck");
const GEAR_WRISTS = array(KEY => 'wrists', TITLE => "Wrists");
const GEAR_LEFT_HAND = array(KEY => 'left_hand', TITLE => "Rings");
const GEAR_RIGHT_HAND = array(KEY => 'right_hand', TITLE => "Rings");
const GEAR_JOB_CRYSTAL = array(KEY => 'job_crystal', TITLE => "Job Crystal");