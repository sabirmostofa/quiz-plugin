<?php
  class QuizQuestion {
      
    var $id=false;
    var $title="";
    var $description="";
    var $type="s";
    var $quiz_id=0;
    var $display_order=0;
    var $display_title="y"; 
    
    var $answers=false;
    var $_answersLoaded=false;
      
    const TYPE_SINGLE_ANSWER = "s";
    const TYPE_MULTIPLE_ANSWER = "m";
      
    public function QuizQuestion($data = false) {
        
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
        
        if(isset($data["type"]))
            $this->type=$data["type"];
        
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
        $d=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_QUESTIONS . " WHERE id = $id ;" , ARRAY_A);
        if(empty($d)) {
            throw new QuizException("Question doesnt exist for provided id [$id]" , QuizException::NO_DATA);   
            return false;
        }
        $this->setData($d);   
        return true;
    }
    public function isSingleAnswerType() {
        return $this->type == self::TYPE_SINGLE_ANSWER;
    }
    public function isMultipleAnswerType() {
        return $this->type == self::TYPE_MULTIPLE_ANSWER;   
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
    public function getType() {
        return $this->type;   
    }
    public function getQuizId() {
        return $this->quiz_id;   
    }
    public function getDisplayOrder() {
        return $this->display_order;
    }       
    public function shouldDisplayTitle() {
        return $this->display_title == "y";
    }
    public function getAnswers() {
        global $wpdb;
        if($this->_answersLoaded === FALSE) {
            $this->_answersLoaded=true;
            $d=$wpdb->get_results("SELECT * FROM " . QUIZ_DB_TABLE_ANSWERS . " WHERE question_id = " . $this->getId());
            if($d) {
                $this->answers=array();
                foreach($d as $ad) {
                    $this->answers[]=new QuizAnswer($ad);
                }   
            }
        }   
        return $this->answers;
    }
    
    public function save() {
        global $wpdb;
        $id=$this->getId();
        if(empty($id)) {
            // insert
            $r=$wpdb->insert(QUIZ_DB_TABLE_QUESTIONS , array("title" => $this->getTitle(), "description" => $this->getDescription(), "type" => $this->getType(), "quiz_id" => $this->getQuizId(), "display_order" => $this->getDisplayOrder(), "display_title" => $this->display_title) , array("%s" , "%s" , "%s", "%d" , "%d", "%s"));   
            if($r) {
                $id=$wpdb->insert_id;
                $this->setData("id" , $id);   
            }
            return $r !== FALSE;
        }   
        else {
            // update
            $r=$wpdb->update(QUIZ_DB_TABLE_QUESTIONS , array("id" => $id, "title" => $this->getTitle(), "description" => $this->getDescription(), "type" => $this->getType(), "quiz_id" => $this->getQuizId(), "display_order" => $this->getDisplayOrder(), "display_title" => $this->display_title) , array("id" => $id) , array("%d", "%s" , "%s" , "%s", "%d" , "%d", "%s") , "%d");   
            return $r !== FALSE;
        }
        
    }
    
    public function delete() {
        global $wpdb;
        
        $id=$this->getId();
        if(empty($id))
            return false;
        
        // delete all answers
        $answers=$this->getAnswers();
        if($answers) {
            foreach($answers as $answer) {
                $answer->delete();
            }   
        }
        // delete question
        $wpdb->query("DELETE FROM " . QUIZ_DB_TABLE_QUESTIONS . " WHERE id = $id ;");
           
    }
      
  }

