<?php
class XIV_DATA implements \JsonSerializable{
    public $characters; //CHARACTERS
    public $race_popuation; //RACE_POPULATION
    public $realms; //REALM[]
    public $job_population; //JOB_POPULATION
    public $grandcompany_population;
    public $beast_tribes;
    public $meta;
    public function jsonSerialize()
    {
        return [
            'characters' => $this->characters,
            'racedistribution' => $this->race_popuation,
            'realms' => $this->realms,
            'jobs' => $this->job_population,
            'grandcompany' => $this->grandcompany_population,
            'beasttribes' => $this->beast_tribes,
            'meta' => $this->meta
        ];
    }
}