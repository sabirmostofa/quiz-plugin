<?php
  class QuizSection {
      
    var $id=false;
    var $title="";
    var $description="";
    var $display_at_result="y";
    var $quiz_id=0;
    var $display_order=0;
    var $display_title="y";   
      
    public function QuizSection($data = false) {
        
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
        
        if(isset($data["title"]))
            $this->title=$data["title"];
        
        if(isset($data["description"]))
            $this->description=$data["description"];
        
        if(isset($data["display_at_result"]))
        {
            if(is_bool($data["display_at_result"])) {
                $data["display_at_result"]=$data["display_at_result"]?"y":"n";   
            }
            $this->display_at_result=$data["display_at_result"];
        }
        if(isset($data["quiz_id"]))
            $this->quiz_id=$data["quiz_id"];
        
        if(isset($data["display_order"]))
            $this->display_order=$data["display_order"];
         if(isset($data["display_title"]))
                $this->display_title=$data["display_title"];
        
        return $this;
    }   
    public function loadById($id) {
        global $wpdb;
        $d=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_SECTIONS . " WHERE id = $id ;" , ARRAY_A);
        if(empty($d)) {
            throw new QuizException("Section doesnt exist for provided id [$id]" , QuizException::NO_DATA);   
            return false;
        }
        $this->setData($d);   
        return true;
    }
    public function shouldDisplayTitleAtResult() {
        return $this->display_at_result == "y";   
    }
    public function shouldDisplayTitle() {
        return $this->display_title == "y";
    }
    public function getId() {
        return $this->id;   
    }
    public function getTitle() {
        return $this->title;   
    }
    public function getDescription() {
        return $this->description;   
    }
    public function getQuizId() {
        return $this->quiz_id;   
    }
    public function getDisplayOrder() {
        return $this->display_order;
    }       
    
    public function save() {
        global $wpdb;
        $id=$this->getId();
        if(empty($id)) {
            // insert
            $r=$wpdb->insert(QUIZ_DB_TABLE_SECTIONS , array("title" => $this->getTitle(), "description" => $this->getDescription(), "display_at_result" => $this->display_at_result, "quiz_id" => $this->getQuizId(), "display_order" => $this->getDisplayOrder(), "display_title" => $this->display_title) , array("%s" , "%s" , "%s", "%d" , "%d", "%s"));   
            if($r) {
                $id=$wpdb->insert_id;
                $this->setData("id" , $id);   
            }
            return $r !== FALSE;
        }   
        else {
            // update
            $r=$wpdb->update(QUIZ_DB_TABLE_SECTIONS , array("id" => $id, "title" => $this->getTitle(), "description" => $this->getDescription(), "display_at_result" => $this->display_at_result, "quiz_id" => $this->getQuizId(), "display_order" => $this->getDisplayOrder(), "display_title" => $this->display_title) , array("id" => $id) , array("%d" , "%s" , "%s" , "%s", "%d" , "%d", "%s") , "%d");   
            return $r !== FALSE;
        }
        
    }
    
    public function delete() {
        global $wpdb;
        
        $id=$this->getId();
        if(empty($id))
            return false;
        
        // delete section
        $wpdb->query("DELETE FROM " . QUIZ_DB_TABLE_SECTIONS . " WHERE id = $id ;");
           
    }
      
  }

