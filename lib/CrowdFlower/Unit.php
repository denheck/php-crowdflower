<?php

namespace CrowdFlower;

/**
 *
 */
class Unit extends Base implements CommonInterface
{
  private $job_id = null;
  private $id = null;
  private $attributes = null;

  private function __construct($job_id, $id = null){
    $this->setJobId($job_id);
    if($id !== null){
      $this->setId($id);
      $this->setAttributes($this->read());
    }
  }



  public function read(){
    if($this->getId() === null){ return new CrowdFlowerException('unit_id'); }
    if($this->getJobId() === null){ return new CrowdFlowerException('job_id'); }

    $url = "jobs/" . $this->getJobId() . "/units/" . $this->getId();

    return $this->sendRequest("GET", $url);

  }

  public function create(){
    if($this->getAttributes() === null){ return new CrowdFlowerException('unit_attributes'); }
    if($this->getJobId() === null){ return new CrowdFlowerException('job_id'); }

    $url = "jobs/" . $this->getJobId() . "/units/?";
    $parameters = $this->serializeAttributes($this->getAttributes());
    $url .= $parameters;

    return $this->sendRequest("POST", $url);
  }

  public function update(){
    if($this->getJobId() === null){ return new CrowdFlowerException('job_id'); }
    if($this->getId() === null){ return new CrowdFlowerException('unit_id'); }
    if($this->getAttributes() === null){ return new CrowdFlowerException('unit_attributes'); }

    $url = "jobs/" . $this->getJobId() . "/units/" . $this->getId() . "?";
    $parameters = $this->serializeAttributes($this->getAttributes());
    $url .= $parameters;

    return $this->sendRequest("PUT", $url);
  }

  public function delete(){
    if($this->getJobId() === null){ return new CrowdFlowerException('job_id'); }
    if($this->getId() === null){ return new CrowdFlowerException('unit_id'); }

    $url = "jobs/" . $this->getJobId() . "/units/" . $this->getId();

    return $this->sendRequest("DELETE", $url);
  }

  public function status(){
    if($this->getJobId() === null){ return new CrowdFlowerException('job_id'); }

    $url = "jobs/" . $this->getJobId() . "/units/ping";

    return $this->sendRequest("GET", $url);
  }

  public function cancel(){
    if($this->getJobId() === null){ return new CrowdFlowerException('job_id'); }
    if($this->getId() === null){ return new CrowdFlowerException('unit_id'); }

    $url = "jobs/" . $this->getJobId() . "/units/" . $this->getId() . "/cancel";

    return $this->sendRequest("PUT", $url);
  }


  public function split($on, $with = " "){
    if($this->getJobId() === null){ return new CrowdFlowerException('job_id'); }

    $url = "jobs/" . $this->getJobId() . "/units/split?";
    $parameters = "on=" . url_encode($on) . "&with=" . url_encode($with);
    $url .= $parameters;

    return $this->sendRequest("PUT", $url);
  }




  public function getJudgments(){
    $url = "jobs/" . $this->getJobId() . "/units/" . $this->getId() . "/judgments";
    return $this->sendRequest("GET", $url);
  }

  private function setJobId($job_id){
    $this->job_id = $job_id;
    return true;
  }

  public function getJobId(){
    return $this->job_id;
  }


  public function setId($id){
    $this->id = $id;
    return true;
  }

  public function getId(){
    return $this->id;
  }

  public function setAttributes($data){
    $this->attributes = $data;
    return true;
  }

  public function getAttributes(){
    return $this->attributes;
  }



  private function serializeAttributes($parameters){
    $parameters_str = "";
    $i = 0;
    foreach($parameters as $k => $v){
      if($i++ > 0){
        $parameters_str .= "&";
      }
      $parameters_str .= "unit[" . url_encode($k) . "]=" . url_encode($v);
    }
    return $parameters_str;
  }

}
