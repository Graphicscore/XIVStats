<?php

$TYPE_ALL = 1;
$TYPE_ACTIVE = 2;

class CHARACTERS {
    public $all;
    public $active;
    public $deleted;

    public function __construct($all, $active, $deleted){
        $this->all = $all;
        $this->active = $active;
        $this->deleted = $deleted;
    }
}

class REALM_POPULATION {
    public $name;
    public $count;

    public function __construct($name, $count){
        $this->name = $name;
        $this->count = $count;
    }

    static function Create($realm_array, $target_realm_count) {
        $all_realm_population = [];

        foreach ($realm_array as $key => $value) {
            $population = new REALM_POPULATION(
                $value,
                getValueFromArray($target_realm_count, $value)
            );
            array_push($all_realm_population,$population);
        }
        return $all_realm_population;
    }
}

class REALM_AGGREGATION {
    public $count;
    public $distribution; //[] REALM_POPULATION

    public function __construct($count, $distribution) {
        $this->count = $count;
        $this->distribution = $distribution;
    }
}

class REALM implements \JsonSerializable {
    public $name;
    public $all_realm_aggregation;
    public $active_realm_aggregation;

    public function __construct($name, $all_realm_aggregation, $active_realm_aggregation) {
        $this->name = $name;
        $this->all_realm_aggregation = $all_realm_aggregation;
        $this->active_realm_aggregation = $active_realm_aggregation;
    }

    public function jsonSerialize()
    {
        return [
            $this->name => [
                'all' => json_encode($this->all_realm_aggregation),
                'active' => json_encode($this->active_realm_aggregation),
            ]
        ];
    }
}

class RACE_POPULATION_ENTRY {
    public $name;
    public $female;
    public $male;

    public function __construct($name, $female, $male) {
        $this->name = $name;
        $this->female = $female;
        $this->male = $male;
    }
}

class RACE_POPULATION implements \JsonSerializable {
    public $all_population; //RACE_POPULATION_ENTRY[]
    public $active_population; //RACE_POPULATION_ENTRY[]

    public function __construct($all_population, $active_population){
        $this->all_population = $all_population;
        $this->active_population = $active_population;
    }

    public static function Create($all_gender_count_data,$active_gender_count_data){
        $active = [];
        $all = [];
        foreach ($all_gender_count_data as $key => $value) {
            $female_pop = getValueFromArray($value, "female");
            $male_pop = getValueFromArray($value, "male");
            $population = new RACE_POPULATION_ENTRY($key, $female_pop, $male_pop);
            array_push($all,$population);
        }
        foreach ($active_gender_count_data as $key => $value) {
            $female_pop = getValueFromArray($value, "female");
            $male_pop = getValueFromArray($value, "male");
            $population = new RACE_POPULATION_ENTRY($key, $female_pop, $male_pop);
            array_push($active,$population);
        }
        return new RACE_POPULATION($all,$active);
    }

    public function jsonSerialize()
    {
        return [
            'all' => json_encode($this->all_population),
            'active' => json_encode($this->active_population),
        ];
    }
}

class JOB_POPULATION_ENTRY {
    public $name;
    public $count;
    public $role;

    public function __construct($name, $count, $role){
        $this->name = $name;
        $this->count = $count;
        $this->role = $role;
    }
}

class JOB_POPULATION implements \JsonSerializable{
    public $all_population;
    public $active_population;

    public function __construct($all_population, $active_population){
        $this->all_population = $all_population;
        $this->active_population = $active_population;
    }

    public static function Create($all_classes, $active_classes){
        $all = [];
        $active = [];
        foreach ($all_classes as $key => $value) {
            $name = $value["TYPE"]["TITLE"];
            $count = $value["COUNT"];
            $role = $value["TYPE"]["ROLE"];
            $pop = new JOB_POPULATION_ENTRY($name,$count,$role);
            array_push($all,$pop);
        };

        foreach ($active_classes as $key => $value) {
            $name = $value["TYPE"]["TITLE"];
            $count = $value["COUNT"];
            $role = $value["TYPE"]["ROLE"];
            $pop = new JOB_POPULATION_ENTRY($name,$count,$role);
            array_push($active,$pop);
        };

        return new JOB_POPULATION($all,$active);
    }

    public function jsonSerialize()
    {
        return [
            'all' => json_encode($this->all_population),
            'active' => json_encode($this->active_population),
        ];
    }
}

class GRAND_COMPANY_POPULATION_ENTRY {
    public $immortalflames;
    public $maelstrom;
    public $twinadder;
    public $none;
    public function __construct($immortalflames,$maelstrom,$twinadder,$none) {
        $this->immortalflames = $immortalflames;
        $this->maelstrom = $maelstrom;
        $this->twinadder = $twinadder;
        $this->none = $none;
    }
}

class GRAND_COMPANY_POPULATION implements \JsonSerializable {
    public $all_population;
    public $active_population;

    public function __construct($all_population, $active_population){
        $this->all_population = $all_population;
        $this->active_population = $active_population;
    }

    public static function Create($all_grandcompany, $active_grandcompany){
        $all = new GRAND_COMPANY_POPULATION_ENTRY(
            $all_grandcompany["Immortal Flames"],
            $all_grandcompany["Maelstrom"],
            $all_grandcompany["Order of the Twin Adder"],
            $all_grandcompany["none"]
        );
        $active = new GRAND_COMPANY_POPULATION_ENTRY(
            $active_grandcompany["Immortal Flames"],
            $active_grandcompany["Maelstrom"],
            $active_grandcompany["Order of the Twin Adder"],
            $active_grandcompany["none"]
        );
        return new GRAND_COMPANY_POPULATION($all,$active);
    }

    public function jsonSerialize()
    {
        return [
            'all' => json_encode($this->all_population),
            'active' => json_encode($this->active_population),
        ];
    }
}