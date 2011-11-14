<?php
  class QuizBadge {
    
    var $id=false;
    var $content="";
    var $range_min=0;
    var $range_max=0;
    var $quiz_id=0;
    var $meta_description="";
    var $meta_title="";
    var $is_random="n";

    public function QuizBadge($data = false) {
        
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
        
        if(isset($data["content"]))
            $this->content=$data["content"];
        
        if(isset($data["range_min"]))
            $this->range_min=$data["range_min"];
        
        if(isset($data["range_max"]))
            $this->range_max=$data["range_max"];
        
        if(isset($data["quiz_id"]))
            $this->quiz_id=$data["quiz_id"];
        
       if(isset($data["meta_description"]))
            $this->meta_description=$data["meta_description"]; 
       if(isset($data["meta_title"]))
            $this->meta_title=$data["meta_title"];
       
        if(isset($data["is_random"]))
            $this->is_random=$data["is_random"];
       

        return $this;
    }

    public function loadById($id) {
        global $wpdb;
        $d=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_BADGES . " WHERE id = $id ;" , ARRAY_A);
        if(empty($d)) {
            throw new QuizException("Badge doesnt exist for provided id [$id]" , QuizException::NO_DATA);   
            return false;
        }
        $this->setData($d);   
        return true;
    }
    public function getId() {
        return $this->id;   
    }
    public function isRandom() {
        return $this->is_random == "y";
    }
    public function getContent() {
        return $this->content;   
    }
    public function getContentPortion($len) {
        return htmlspecialchars(wysiwyg_db_to_html(substr($this->content , 0, $len)));   
    }
    public function getQuizId() {
        return $this->quiz_id;   
    }
    public function getRangeMin() {
        return $this->range_min;   
    }
    public function getRangeMax() {
        return $this->range_max;   
    }
    public function getMetaDescription() {
        return $this->meta_description;   
    }
    public function getMetaTitle() {
        return $this->meta_title;
    }
    public function isValueInRange($value) {
        $r=true;
        if($this->range_min > 0 && $this->range_min > $value)
            $r=false;
        if($this->range_max > 0 && $this->range_max < $value)
            $r=false;  
        return $r !== FALSE;
    }
    public function delete() {
        global $wpdb;
        
        $id=$this->getId();
        if(empty($id))
            return false;
            
        // delete badge
        $wpdb->query("DELETE FROM " . QUIZ_DB_TABLE_BADGES . " WHERE id = $id ;");
           
    }
    public function save() {
        global $wpdb;
        $id=$this->getId();
        if(empty($id)) {
            // insert
            $r=$wpdb->insert(QUIZ_DB_TABLE_BADGES , array("content" => $this->getContent(), "range_min" => $this->getRangeMin(), "range_max" => $this->getRangeMax(), "quiz_id" => $this->getQuizId(), "meta_description" => $this->meta_description, "is_random" => $this->is_random, "meta_title" => $this->meta_title) , array("%s" , "%s" , "%s" , "%d", "%s", "%s" , "%s"));
            if($r) {
                $id=$wpdb->insert_id;
                $this->setData("id" , $id);   
            }
            return $r !== FALSE;
        }   
        else {
            // update
            $r=$wpdb->update(QUIZ_DB_TABLE_BADGES , array("id" => $id, "content" => $this->getContent(), "range_min" => $this->getRangeMin(), "range_max" => $this->getRangeMax(), "quiz_id" => $this->getQuizId(), "meta_description" => $this->meta_description, "is_random" => $this->is_random,  "meta_title" => $this->meta_title) , array("id" => $id) , array("%d" , "%s" , "%s" , "%s", "%d", "%s", "%s", "%s") , "%d");
            return $r !== FALSE;
        }
        
    }
  }

