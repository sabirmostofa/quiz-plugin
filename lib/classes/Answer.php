<?php
  class QuizAnswer {
      
    var $id=false;
    var $question_id=0;
    var $content="";
    var $response="";
    var $value=0;
    
    public function QuizAnswer($data = false) {
        
        if(is_array($data) || is_object($data)) {
            $this->setData($data);   
        }
        if(is_numeric($data)) {
            $this->loadById($data);       
        }
    }
    public function setData($data, $value=false) {
        if(is_array($data) || is_object($data)) {
            if(is_object($data)) {
                $data=get_object_vars($data);   
            }
        }
        else {
            $key=$data;
            $data=array();
            $data[$key]=$value;    
        }
        
        if(isset($data["id"]))
            $this->id=$data["id"];
        if(isset($data["question_id"]))
            $this->question_id=$data["question_id"];
        if(isset($data["content"]))
            $this->content=$data["content"];
        if(isset($data["response"]))
            $this->response=$data["response"];
        if(isset($data["value"]))
            $this->value=$data["value"];
        
        
        
        return $this;
    }   
    public function loadById($id) {
        global $wpdb;
        $d=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_ANSWERS . " WHERE id = $id ;" , ARRAY_A);
        if(empty($d)) {
            throw new QuizException("Answer doesnt exist for provided id [$id]" , QuizException::NO_DATA);   
            return false;
        }
        $this->setData($d);   
        return true;
    }
    public function getId() {
        return $this->id;   
    }
    public function getQuestionId() {
        return $this->question_id;   
    }
    public function getContent() {
        return $this->content;   
    }
    public function getResponse() {
        return $this->response;   
    }
    public function getValue() {
        return $this->value;   
    }
    
    public function save() {
        global $wpdb;
        $id=$this->getId();
        $data=array(
          'id' => $id,
          'question_id' => $this->getQuestionId(),
          'content' => $this->getContent(),
          'response' => $this->getResponse(),
          'value' => $this->getValue()
        );
        $dataFormat=array("%d", "%d", "%s", "%s", "%d");
        if(empty($id)) {
            // insert
            unset($data["id"]);
            array_shift($dataFormat);
            $r=$wpdb->insert(QUIZ_DB_TABLE_ANSWERS , $data , $dataFormat);   
            if($r) {
                $id=$wpdb->insert_id;
                $this->setData("id" , $id);   
            }
            return $r !== FALSE;
        }   
        else {
            // update
            $r=$wpdb->update(QUIZ_DB_TABLE_ANSWERS , $data , array("id" => $id) , $dataFormat , "%d");   
            return $r !== FALSE;
        }
        
    }
    
    public function delete() {
        global $wpdb;
        
        $id=$this->getId();
        if(empty($id))
            return false;
        
        // delete answer
        $wpdb->query("DELETE FROM " . QUIZ_DB_TABLE_ANSWERS . " WHERE id = $id ;");
           
    }
      
  }

