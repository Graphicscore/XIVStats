<?php

include './constans.php';
include './models/XIV_MODELS.php';
include './models/XIV_DATA.php';

// Helper function to fetch the sum of all values in the array, where the array key matches one of the specified realm names
function sumInRegion($data, $regional_realms) {
        return array_sum(array_intersect_key($data, array_flip($regional_realms)));
}

// Helper function that returns zero if the value supplied isn't set
function getValue($value) {
    return isset($value) ? $value : 0;
}

// Helper function to return the value of the requested key, or zero if one isn't available
function getValueFromArray($data, $key) {
        return getValue($data[$key]);
}

// Helper function to increment class count into the supplied total array
function handleClass($row, $classDef, &$totalArray) {
    if(!isset($totalArray[$classDef[KEY]])) {
        $totalArray[$classDef[KEY]] = array();
        $totalArray[$classDef[KEY]][COUNT] = 0;
        $totalArray[$classDef[KEY]][TYPE] = $classDef;
    }
    $level = isset($row[$classDef[KEY]]) ? $row[$classDef[KEY]] : 0;
    if($level > 0) {
        $totalArray[$classDef[KEY]][COUNT]++;
    }
    return 0;
}

function handleEurekaLevel($eurekaLevel, $mounts, &$totalArray) {
    $eurekaCategory = "None";
    if(in_array("Demi-Ozma", $mounts)) {
        $eurekaCategory = "Baldesion Arsenal";
    } else if($eurekaLevel > 50) {
        $eurekaCategory = "Hydatos (50-60)";
    } else if($eurekaLevel > 35) {
        $eurekaCategory = "Pyros (35-50)";
    } else if($eurekaLevel > 20) {
        $eurekaCategory = "Pagos (20-35)";
    } else if($eurekaLevel > 0) {
        $eurekaCategory = "Anemos (1-20)";
    }

    $totalArray[$eurekaCategory]++;
}

function handleGear($row, $gearSlot, &$totalArray) {
    // If counters not initialised, then do so
    if(!isset($totalArray[$gearSlot[TITLE]])) {
        $totalArray[$gearSlot[TITLE]] = array();
    }

    // Check if there's even an item in the slot
    if(isset($row[$gearSlot[KEY]])) {
        $itemId = $row[$gearSlot[KEY]];

        if(!isset($totalArray[$gearSlot[TITLE]][$itemId])) {
            $totalArray[$gearSlot[TITLE]][$itemId] = 0;
        }
        
        $totalArray[$gearSlot[TITLE]][$itemId]++;
    }
}

$conn_info = parse_ini_file("templateconfig.ini");

$date = date("F Y");

// Create DB Connection
$db = new mysqli($conn_info["host"], $conn_info["username"], $conn_info["password"]);

// Check DB Connection
if ($db->connect_error) {
	die("Connection failed: " . $db->connect_error);
}

// Select DB
if (! $db->select_db($conn_info["database"])) {
        die("Couldn't find DB");
}

$american_realm_array = array("Behemoth","Brynhildr","Diabolos","Exodus","Famfrit","Hyperion",
                              "Lamia","Leviathan","Malboro","Ultros","Adamantoise","Balmung",
                              "Cactuar","Coeurl","Faerie","Gilgamesh","Goblin","Jenova","Mateus",
                              "Midgardsormr","Sargatanas","Siren","Zalera","Excalibur");
sort($american_realm_array);

$japanese_realm_array = array("Alexander","Bahamut","Durandal","Fenrir","Ifrit","Ridill","Tiamat","Ultima",
                              "Valefor","Yojimbo","Zeromus","Anima","Asura","Belias","Chocobo","Hades",
                              "Ixion","Mandragora","Masamune","Pandaemonium","Shinryu","Titan","Aegis",
                              "Atomos","Carbuncle","Garuda","Gungnir","Kujata","Ramuh","Tonberry","Typhon","Unicorn");
sort($japanese_realm_array);

$european_realm_array = array("Cerberus","Lich","Moogle","Odin","Phoenix","Ragnarok","Shiva","Zodiark","Louisoix","Omega",
                              "Spriggan","Twintania");
sort($european_realm_array);

$gear_cache = array();

$gear_query = $db->query("SELECT * FROM gear_items;", MYSQLI_USE_RESULT);
while($row = $gear_query->fetch_assoc()) {
    $gear_cache[$row['item_id']] = array("name" => $row['name'], "category" => $row['category'], "ilevel" => $row['ilevel']);
}

// Variables
$player_count = 0;
$active_player_count = 0;
$deleted_player_count = 0;
$realm_count = array();
$active_realm_count = array();
$gc_count = array();
$active_gc_count = array();
$race_gender_count = array();
$active_race_gender_count = array();

$classes = array();
$active_classes = array();

$eureka = array();
$eureka["Baldesion Arsenal"] = 0;
$eureka["Hydatos (50-60)"] = 0;
$eureka["Pyros (35-50)"] = 0;
$eureka["Pagos (20-35)"] = 0;
$eureka["Anemos (1-20)"] = 0;
$eureka["None"] = 0;

$active_eureka = array();
$active_eureka["Baldesion Arsenal"] = 0;
$active_eureka["Hydatos (50-60)"] = 0;
$active_eureka["Pyros (35-50)"] = 0;
$active_eureka["Pagos (20-35)"] = 0;
$active_eureka["Anemos (1-20)"] = 0;
$active_eureka["None"] = 0;

$gear = array();
$active_gear = array();

$gear_ilevel = 0;
$active_gear_ilevel = 0;

$pre_arealmreborn = 0;
$pre_heavensward = 0;
$pre_stormblod = 0;
$pre_shadowbringers = 0;
$pre_endwalker = 0;

$ps4_collectors = 0;
$pc_collectors = 0;
$heavensward_collectors = 0;
$stormblood_collectors = 0;
$shadowbringers_collectors = 0;
$endwalker_collectors = 0;
$arrartbook = 0;
$sbartbook = 0;
$beforemeteor = 0;
$beforethefall = 0;
$soundtrack = 0;
$moogleplush = 0;
$saw_eternal_bond = 0;
$did_eternal_bond = 0;
$comm50 = 0;
$hildibrand = 0;
$sightseeing = 0;
$oceanfishing_5k = 0;
$oceanfishing_10k = 0;

$beast_tribes = array();
$beast_tribes["Kobold"] = 0;
$beast_tribes["Sahagin"] = 0;
$beast_tribes["Amaljaa"] = 0;
$beast_tribes["Sylph"] = 0;
$beast_tribes["Ixal"] = 0;
// Heavensward
$beast_tribes["Vanu Vanu"] = 0;
$beast_tribes["Vath"] = 0;
$beast_tribes["Moogle"] = 0;
// Stormblood
$beast_tribes["Kojin"] = 0;
$beast_tribes["Ananta"] = 0;
$beast_tribes["Namazu"] = 0;
// Shadowbringers
$beast_tribes["Pixie"] = 0;
$beast_tribes["Qitari"] = 0;
$beast_tribes["Dwarf"] = 0;

$player_overview_query = $db->query("SELECT * FROM tblplayers p LEFT JOIN character_gear_sets s ON p.id = s.player_id;", MYSQLI_USE_RESULT);
while($row = $player_overview_query->fetch_assoc()) {
    // Skip deleted characters
    if(isset($row["character_status"]) && $row["character_status"] == "DELETED") {
        $deleted_player_count++;
        continue;
    }

    // Expand the mounts & minions to an array
    $mounts = isset($row["mounts"]) ? str_getcsv($row["mounts"]) : array();
    $minions = isset($row["minions"]) ? str_getcsv($row["minions"]) : array();

    // Basic data
    $realm = isset($row["realm"]) ? $row["realm"] : 'Unknown';
    $grand_company = isset($row["grand_company"]) ? $row["grand_company"] : 'Unknown';
    $race = isset($row["race"]) ? $row["race"] : 'Unknown';
    $gender = isset($row["gender"]) ? $row["gender"] : 'Unknown';
    
    // Fetch total number of players in database
    $player_count++;
    // Fetch realm player counts
    if(!array_key_exists($realm, $realm_count)) {
            $realm_count[$realm] = 0;
    }
    $realm_count[$realm]++;
    // Fetch grand company player count
    if(!array_key_exists($grand_company, $gc_count)) {
            $gc_count[$grand_company] = 0;
    }
    $gc_count[$grand_company]++;
    // Fetch race and gender player count
    if(!array_key_exists($race, $race_gender_count)) {
            $race_gender_count[$race] = array();
    }
    if(!array_key_exists($gender, $race_gender_count[$race])) {
            $race_gender_count[$race][$gender] = 0;
    }
    $race_gender_count[$race][$gender]++;

    handleClass($row, CLASS_GLA, $classes);
    handleClass($row, CLASS_PUG, $classes);
    handleClass($row, CLASS_MRD, $classes);
    handleClass($row, CLASS_LNC, $classes);
    handleClass($row, CLASS_ARC, $classes);
    handleClass($row, CLASS_ROG, $classes);
    handleClass($row, CLASS_CNJ, $classes);
    handleClass($row, CLASS_THM, $classes);
    handleClass($row, CLASS_ACN, $classes);
    handleClass($row, CLASS_SCH, $classes);
    handleClass($row, CLASS_DRK, $classes);
    handleClass($row, CLASS_MCH, $classes);
    handleClass($row, CLASS_AST, $classes);
    handleClass($row, CLASS_SAM, $classes);
    handleClass($row, CLASS_RDM, $classes);
    handleClass($row, CLASS_BLU, $classes);
    handleClass($row, CLASS_GNB, $classes);
    handleClass($row, CLASS_DNC, $classes);
    handleClass($row, CLASS_CRP, $classes);
    handleClass($row, CLASS_BSM, $classes);
    handleClass($row, CLASS_ARM, $classes);
    handleClass($row, CLASS_GSM, $classes);
    handleClass($row, CLASS_LWR, $classes);
    handleClass($row, CLASS_WVR, $classes);
    handleClass($row, CLASS_ALC, $classes);
    handleClass($row, CLASS_CUL, $classes);
    handleClass($row, CLASS_MIN, $classes);
    handleClass($row, CLASS_BTN, $classes);
    handleClass($row, CLASS_FSH, $classes);

    handleGear($row, GEAR_MAIN_HAND, $gear);
    handleGear($row, GEAR_OFF_HAND, $gear);
    handleGear($row, GEAR_HEAD, $gear);
    handleGear($row, GEAR_BODY, $gear);
    handleGear($row, GEAR_HANDS, $gear);
    handleGear($row, GEAR_LEGS, $gear);
    handleGear($row, GEAR_FEET, $gear);
    handleGear($row, GEAR_EARS, $gear);
    handleGear($row, GEAR_NECK, $gear);
    handleGear($row, GEAR_WRISTS, $gear);
    handleGear($row, GEAR_LEFT_HAND, $gear);
    handleGear($row, GEAR_RIGHT_HAND, $gear);
    handleGear($row, GEAR_JOB_CRYSTAL, $gear);

    handleEurekaLevel($row["level_eureka"], $mounts, $eureka);


    // Pre-orders
    $pre_arealmreborn += isset($row["prearr"]) && $row["prearr"] == 1 ? 1 : 0;
    $pre_heavensward += isset($row["prehw"]) && $row["prehw"] == 1 ? 1 : 0;
    $pre_stormblod += isset($row["presb"]) && $row["presb"] == 1 ? 1 : 0;
    $pre_shadowbringers += isset($row["preshb"]) && $row["preshb"] == 1 ? 1 : 0;
    $pre_endwalker += in_array("Wind-up Palom",$minions) ? 1 : 0;

    // Collectors Edition
    $ps4_collectors += isset($row["ps4collectors"]) && $row["ps4collectors"] == 1 ? 1 : 0;
    $pc_collectors += isset($row["arrcollector"]) && $row["arrcollector"] == 1 ? 1 : 0;
    $heavensward_collectors += in_array("Wind-up Kain", $minions) ? 1 : 0;
    $stormblood_collectors += in_array("Wind-up Bartz", $minions) ? 1 : 0;
    $shadowbringers_collectors += in_array("Grani", $mounts) ? 1 : 0;
    $endwalker_collectors += in_array("Wind-up Porom", $minions) ? 1 : 0;

    // Physical Items
    $arrartbook += isset($row["arrartbook"]) && $row["arrartbook"] == 1 ? 1 : 0;
    $beforemeteor += isset($row["beforemeteor"]) && $row["beforemeteor"] == 1 ? 1 : 0;
    $beforethefall += isset($row["beforethefall"]) && $row["beforethefall"] == 1 ? 1 : 0;
    $soundtrack += isset($row["soundtrack"]) && $row["soundtrack"] == 1 ? 1 : 0;
    $moogleplush += isset($row["moogleplush"]) && $row["moogleplush"] == 1 ? 1 : 0;
    $sbartbook += isset($row["sbartbook"]) && $row["sbartbook"] == 1 ? 1 : 0;
    $sbartbooktwo += isset($row["sbartbooktwo"]) && $row["sbartbooktwo"] == 1 ? 1 : 0;

    // Eternal Bond
    $saw_eternal_bond += isset($row["saweternalbond"]) && $row["saweternalbond"] == 1 ? 1 : 0;
    $did_eternal_bond += isset($row["dideternalbond"]) && $row["dideternalbond"] == 1 ? 1 : 0;

    // Player Commendations
    $comm50 += isset($row["comm50"]) && $row["comm50"] == 1 ? 1 : 0;

    // Hildibrand
    $hildibrand += isset($row["hildibrand"]) && $row["hildibrand"] == 1 ? 1 : 0;

    // ARR Sightseeing Log
    $sightseeing += isset($row["sightseeing"]) && $row["sightseeing"] == 1 ? 1 : 0;

    // Ocean Fishing
    $oceanfishing_5k += in_array("The Major-General", $minions) == 1 ? 1 : 0;
    $oceanfishing_10k += in_array("Hybodus", $mounts) == 1 ? 1 : 0;

    // Beast Tribes with dedicated columns in DB
    // A Realm Reborn
    $beast_tribes["Kobold"] += isset($row["kobold"]) && $row["kobold"] == 1 ? 1 : 0;
    $beast_tribes["Sahagin"] += isset($row["sahagin"]) && $row["sahagin"] == 1 ? 1 : 0;
    $beast_tribes["Amaljaa"] += isset($row["amaljaa"]) && $row["amaljaa"] == 1 ? 1 : 0;
    $beast_tribes["Sylph"] += isset($row["sylph"]) && $row["sylph"] == 1 ? 1 : 0;
    // Heavensward
    $beast_tribes["Vanu Vanu"] += isset($row["vanuvanu"]) && $row["vanuvanu"] == 1 ? 1 : 0;
    $beast_tribes["Vath"] += isset($row["vath"]) && $row["vath"] == 1 ? 1 : 0;
    $beast_tribes["Moogle"] += isset($row["moogle"]) && $row["moogle"] == 1 ? 1 : 0;
    // Stormblood

    // Bast tribes from minions
    $beast_tribes["Ixal"] += in_array("Wind-up Ixal", $minions) ? 1 : 0;
	$beast_tribes["Kojin"] += in_array("Wind-up Kojin", $minions) ? 1 : 0;
	$beast_tribes["Ananta"] += in_array("Wind-up Ananta", $minions) ? 1 : 0;
	$beast_tribes["Namazu"] += in_array("Attendee #777", $minions) ? 1 : 0;
    $beast_tribes["Pixie"] += in_array("Wind-up Pixie", $minions) ? 1 : 0;
    $beast_tribes["Qitari"] += in_array("The Behelmeted Serpent Of Ronka", $minions) ? 1 : 0;
    $beast_tribes["Dwarf"] += in_array("Lalinator 5.H0", $minions) ? 1 : 0;
  
    // Fetch total number of active players in database by checking for the Wind-up Mystel minion received during 5.3 MSQ
    if(in_array("Wind-up Mystel", $minions)) {  $active_player_count++;
        // Fetch realm active player count
        if(!array_key_exists($realm, $active_realm_count)) {
                $active_realm_count[$realm] = 0;
        }
        $active_realm_count[$realm]++;
        // Fetch granc company active player count
        if(!array_key_exists($grand_company, $active_gc_count)) {
                $active_gc_count[$grand_company] = 0;
        }
        $active_gc_count[$grand_company]++;
        // Fetch race and gender active player count
        if(!array_key_exists($race, $active_race_gender_count)) {
                $active_race_gender_count[$race] = array();
        }
        if(!array_key_exists($gender, $active_race_gender_count[$race])) {
                $active_race_gender_count[$race][$gender] = 0;
        }
        $active_race_gender_count[$race][$gender]++;

        handleClass($row, CLASS_GLA, $active_classes);
        handleClass($row, CLASS_PUG, $active_classes);
        handleClass($row, CLASS_MRD, $active_classes);
        handleClass($row, CLASS_LNC, $active_classes);
        handleClass($row, CLASS_ARC, $active_classes);
        handleClass($row, CLASS_ROG, $active_classes);
        handleClass($row, CLASS_CNJ, $active_classes);
        handleClass($row, CLASS_THM, $active_classes);
        handleClass($row, CLASS_ACN, $active_classes);
        handleClass($row, CLASS_SCH, $active_classes);
        handleClass($row, CLASS_DRK, $active_classes);
        handleClass($row, CLASS_MCH, $active_classes);
        handleClass($row, CLASS_AST, $active_classes);
        handleClass($row, CLASS_SAM, $active_classes);
        handleClass($row, CLASS_RDM, $active_classes);
        handleClass($row, CLASS_BLU, $active_classes);
        handleClass($row, CLASS_GNB, $active_classes);
        handleClass($row, CLASS_DNC, $active_classes);
        handleClass($row, CLASS_CRP, $active_classes);
        handleClass($row, CLASS_BSM, $active_classes);
        handleClass($row, CLASS_ARM, $active_classes);
        handleClass($row, CLASS_GSM, $active_classes);
        handleClass($row, CLASS_LWR, $active_classes);
        handleClass($row, CLASS_WVR, $active_classes);
        handleClass($row, CLASS_ALC, $active_classes);
        handleClass($row, CLASS_CUL, $active_classes);
        handleClass($row, CLASS_MIN, $active_classes);
        handleClass($row, CLASS_BTN, $active_classes);
        handleClass($row, CLASS_FSH, $active_classes);

        handleGear($row, GEAR_MAIN_HAND, $active_gear);
        handleGear($row, GEAR_OFF_HAND, $active_gear);

        handleGear($row, GEAR_HEAD, $active_gear);
        handleGear($row, GEAR_EARS, $active_gear);

        handleGear($row, GEAR_BODY, $active_gear);
        handleGear($row, GEAR_NECK, $active_gear);

        handleGear($row, GEAR_HANDS, $active_gear);
        handleGear($row, GEAR_WRISTS, $active_gear);

        handleGear($row, GEAR_LEGS, $active_gear);
        handleGear($row, GEAR_LEFT_HAND, $active_gear);
        handleGear($row, GEAR_RIGHT_HAND, $active_gear);

        handleGear($row, GEAR_FEET, $active_gear);
        handleGear($row, GEAR_JOB_CRYSTAL, $active_gear);

        handleEurekaLevel($row["level_eureka"], $mounts, $active_eureka);
    }
}

ksort($gc_count);
ksort($active_gc_count);
ksort($race_gender_count);
ksort($active_race_gender_count);

// Close DB Connection
$db->close();

$gen_all_realm_america = sumInRegion($realm_count, $american_realm_array);
$gen_all_realm_japan = sumInRegion($realm_count, $japanese_realm_array);
$gen_all_realm_europe = sumInRegion($realm_count, $european_realm_array);

$gen_active_realm_america = sumInRegion($active_realm_count, $american_realm_array);
$gen_active_realm_japan = sumInRegion($active_realm_count, $japanese_realm_array);
$gen_active_realm_europe = sumInRegion($active_realm_count, $european_realm_array);



$characters = new CHARACTERS($player_count,$active_player_count,$deleted_player_count);



$american_realm = new REALM("america", new REALM_AGGREGATION(
    sumInRegion($realm_count, $american_realm_array),
    REALM_POPULATION::Create($american_realm_array, $realm_count)
), new REALM_AGGREGATION(
    sumInRegion($active_realm_count, $american_realm_array),
    REALM_POPULATION::Create($american_realm_array, $active_realm_count)
));

$japanese_realm = new REALM("japan", new REALM_AGGREGATION(
    sumInRegion($realm_count, $japanese_realm_array),
    REALM_POPULATION::Create($japanese_realm_array, $realm_count)
), new REALM_AGGREGATION(
    sumInRegion($active_realm_count, $japanese_realm_array),
    REALM_POPULATION::Create($japanese_realm_array, $active_realm_count)
));

$european_realm = new REALM("europe", new REALM_AGGREGATION(
    sumInRegion($realm_count, $european_realm_array),
    REALM_POPULATION::Create($european_realm_array, $realm_count)
), new REALM_AGGREGATION(
    sumInRegion($active_realm_count, $european_realm_array),
    REALM_POPULATION::Create($european_realm_array, $active_realm_count)
));

$race = RACE_POPULATION::Create($race_gender_count,$active_race_gender_count);

$jobs = JOB_POPULATION::Create($classes,$active_classes);

$grand_company = GRAND_COMPANY_POPULATION::Create($gc_count,$active_gc_count);

$game_editions = [
    'pre_orders' => [
        'arr' => $pre_arealmreborn,
        'heavensward' => $pre_heavensward,
        'stormblood' => $pre_stormblod,
        'shadowbringers' => $pre_shadowbringers,
        'endwalker' => $pre_endwalker
    ],
    'collectors' => [
        'arr_pc' => $pc_collectors,
        'arr_ps4' => $ps4_collectors,
        'heavensward' => $heavensward_collectors,
        'stormblood' => $stormblood_collectors,
        'shadowbringers' => $shadowbringers_collectors,
        'endwalker' => $endwalker_collectors
    ]
];


$meta_data = new META();

$meta_data->aggregation_data->total_characters = 50000000; //currently hardcoded as an approximate value, this should be moved into the databse
$meta_data->aggregation_data->progressed_characters = $characters->all;
$meta_data->aggregation_data->active = true; //this should also be loaded from the databse

$xivdata = new XIV_DATA();
$xivdata->characters = $characters;
$xivdata->race_popuation = $race;
$xivdata->realms = new REALM_CONTAINER($american_realm,$japanese_realm,$european_realm);
$xivdata->job_population = $jobs;
$xivdata->grandcompany_population = $grand_company;
$xivdata->beast_tribes = $beast_tribes;
$xivdata->game_editions = $game_editions;
$xivdata->meta = $meta_data;

$tmp = json_encode($xivdata, JSON_PRETTY_PRINT);
file_put_contents("../data/xivdata.pretty.json",$tmp);
$tmp = json_encode($xivdata);
file_put_contents("../data/xivdata.json",$tmp);
