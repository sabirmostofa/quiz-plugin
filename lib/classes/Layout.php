<?php
  class QuizLayout{
      
      var $id=false;
      var $quiz_id=0;
      var $header_content="";
      var $left_content="";
      var $right_content="";
      var $bottom_content="";
      var $use_at_question="y";
      var $use_at_section="y";
      var $use_at_optin="y";
      var $use_at_result="y";
      
        public function QuizLayout($data = false) {
        
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
            if(isset($data["quiz_id"]))
                $this->quiz_id=$data["quiz_id"];
            if(isset($data["header_content"]))
                $this->header_content=$data["header_content"];
            if(isset($data["left_content"]))
                $this->left_content=$data["left_content"];
            if(isset($data["right_content"]))
                $this->right_content=$data["right_content"];
            if(isset($data["bottom_content"]))
                $this->bottom_content=$data["bottom_content"];
            if(isset($data["use_at_question"]))
            {
                $v=$data["use_at_question"];
                if(is_bool($v))
                    $v=$v?"y":"n";
                $this->use_at_question=$v;
            }
            if(isset($data["use_at_section"]))
            {
                $v=$data["use_at_section"];
                if(is_bool($v))
                    $v=$v?"y":"n";
                $this->use_at_section=$v;
            }
            if(isset($data["use_at_optin"]))
            {
                $v=$data["use_at_optin"];
                if(is_bool($v))
                    $v=$v?"y":"n";
                $this->use_at_optin=$v;
            }
            if(isset($data["use_at_result"]))
            {
                $v=$data["use_at_result"];
                if(is_bool($v))
                    $v=$v?"y":"n";
                $this->use_at_result=$v;
            }
            
            
            
            
            return $this;
        }   
        public function loadById($id) {
            global $wpdb;
            $d=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_LAYOUTS . " WHERE id = $id ;" , ARRAY_A);
            if(empty($d)) {
                throw new QuizException("Layout doesnt exist for provided id [$id]" , QuizException::NO_DATA);   
                return false;
            }
            $this->setData($d);   
            return true;
        }
        public function loadByQuizId($quiz_id) {
            global $wpdb;
            $d=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_LAYOUTS . " WHERE quiz_id = $quiz_id ;" , ARRAY_A);
            if(empty($d)) {
                throw new QuizException("Layout doesnt exist for provided quizid [$quiz_id]" , QuizException::NO_DATA);   
                return false;
            }
            $this->setData($d);   
            return true;
        }
       
        public function getId() {
            return $this->id;   
        }
        public function getQuizId() {
            return $this->quiz_id;   
        }
        public function getHeaderContent() {
            return $this->header_content;   
        }
        public function getLeftContent() {
            return $this->left_content;   
        }
        public function getRightContent() {
            return $this->right_content;   
        }
        public function getBottomContent() {
            return $this->bottom_content;   
        }
        public function shouldUseAtQuestion() {
            return $this->use_at_question == "y";   
        }
        public function shouldUseAtSection() {
            return $this->use_at_section == "y";   
        }
        public function shouldUseAtOptin() {
            return $this->use_at_optin == "y";   
        }
        public function shouldUseAtResult() {
            return $this->use_at_result == "y";   
        }
        
        public function delete() {
            global $wpdb;
            
            $id=$this->getId();
            if(empty($id))
                return false;
                
            // delete layout
            $wpdb->query("DELETE FROM " . QUIZ_DB_TABLE_LAYOUTS . " WHERE id = $id ;");
               
        }
        public function save() {
            global $wpdb;
            $id=$this->getId();
            $data=array(
              'id' => $id,
              'quiz_id' => $this->getQuizId(),
              'header_content' => $this->getHeaderContent(),
              'left_content' => $this->getLeftContent(),
              'right_content' => $this->getRightContent(),
              'bottom_content' => $this->getBottomContent(),
              'use_at_question' => $this->use_at_question,
              'use_at_section' => $this->use_at_section,
              'use_at_optin' => $this->use_at_optin,
              'use_at_result' => $this->use_at_result
            );
            $dataFormat=array("%d", "%d", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s");
            if(empty($id)) {
                // insert
                unset($data["id"]);
                array_shift($dataFormat);
                $r=$wpdb->insert(QUIZ_DB_TABLE_LAYOUTS , $data , $dataFormat);   
                if($r) {
                    $id=$wpdb->insert_id;
                    $this->setData("id" , $id);   
                }
                return $r !== FALSE;
            }   
            else {
                // update
                $r=$wpdb->update(QUIZ_DB_TABLE_LAYOUTS , $data , array("id" => $id) , $dataFormat , "%d");   
                return $r !== FALSE;
            }
            
        }
      
         
  }
