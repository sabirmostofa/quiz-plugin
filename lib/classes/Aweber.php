<?php
  class QuizAweber{
      
      var $id=false;
      var $quiz_id=0;
      var $listname="";
      var $firstname_label="";
      var $lastname_label="";
      var $email_label="";
      var $submit_label="";
      var $content="";
      var $title="";
      var $display_title="y";
      var $skip_optin="n";
      var $custom_field_type="n";
      var $custom_field_label="";
      var $custom_field_value="";

        public function QuizAweber($data = false) {
        
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
            if(isset($data["listname"]))
                $this->listname=$data["listname"];
            if(isset($data["firstname_label"]))
                $this->firstname_label=$data["firstname_label"];
            if(isset($data["lastname_label"]))
                $this->lastname_label=$data["lastname_label"];
            if(isset($data["email_label"]))
                $this->email_label=$data["email_label"];
            if(isset($data["submit_label"]))
                $this->submit_label=$data["submit_label"];
            if(isset($data["content"]))
                $this->content=$data["content"];
            if(isset($data["title"]))
                $this->title=$data["title"];
            if(isset($data["display_title"]))
                $this->display_title=$data["display_title"];
            if(isset($data["skip_optin"]))
                $this->skip_optin=$data["skip_optin"];
            if(isset($data["custom_field_type"]))
                $this->custom_field_type=$data["custom_field_type"];
            if(isset($data["custom_field_label"]))
                $this->custom_field_label=$data["custom_field_label"];
            if(isset($data["custom_field_value"]))
                $this->custom_field_value=$data["custom_field_value"];
            
            return $this;
        }   
        public function loadById($id) {
            global $wpdb;
            $d=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_AWEBER . " WHERE id = $id ;" , ARRAY_A);
            if(empty($d)) {
                throw new QuizException("Aweber details doesnt exist for provided id [$id]" , QuizException::NO_DATA);   
                return false;
            }
            $this->setData($d);   
            return true;
        }
        public function loadByQuizId($quiz_id) {
            global $wpdb;
            $d=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_AWEBER . " WHERE quiz_id = $quiz_id ;" , ARRAY_A);
            if(empty($d)) {
                throw new QuizException("Aweber details doesnt exist for provided quizid [$quiz_id]" , QuizException::NO_DATA);   
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
        public function getListname() {
            return $this->listname;   
        }
        public function getFirstnameLabel() {
            return $this->firstname_label;   
        }
        public function getLastnameLabel() {
            return $this->lastname_label;   
        }
        public function getEmailLabel() {
            return $this->email_label;   
        }
        public function getSubmitLabel() {
            return $this->submit_label;   
        }
        public function getContent() {
            return $this->content;   
        }
        public function getTitle() {
            return $this->title;   
        }
        public function shouldDisplayTitle() {
            return $this->display_title == "y";
        }
        public function shouldSkipOptin() {
            return $this->skip_optin == "y";
        }
        public function getCustomFieldType() {
            return $this->custom_field_type;
        }
        public function getCustomFieldLabel() {
            return $this->custom_field_label;
        }
        public function getCustomFieldValue() {
            return $this->custom_field_value;
        }
        public function delete() {
            global $wpdb;
            
            $id=$this->getId();
            if(empty($id))
                return false;
                
            // delete aweber
            $wpdb->query("DELETE FROM " . QUIZ_DB_TABLE_AWEBER . " WHERE id = $id ;");
               
        }
        public function save() {
            global $wpdb;
            $id=$this->getId();
            $data=array(
              'id' => $id,
              'quiz_id' => $this->getQuizId(),
              'listname' => $this->getListname(),
              'firstname_label' => $this->getFirstnameLabel(),
              'lastname_label' => $this->getLastnameLabel(),
              'email_label' => $this->getEmailLabel(),
              'submit_label' => $this->getSubmitLabel(),
              'content' => $this->getContent(),
              "title" => $this->getTitle(),
              "display_title" => $this->display_title,
                "skip_optin" => $this->skip_optin,
                "custom_field_value" => $this->custom_field_value,
                "custom_field_type" => $this->custom_field_type,
                "custom_field_label" => $this->custom_field_label

            );
            $dataFormat=array("%d", "%d", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s");
            if(empty($id)) {
                // insert
                unset($data["id"]);
                array_shift($dataFormat);
                $r=$wpdb->insert(QUIZ_DB_TABLE_AWEBER , $data , $dataFormat);   
                if($r) {
                    $id=$wpdb->insert_id;
                    $this->setData("id" , $id);   
                }
                return $r !== FALSE;
            }   
            else {
                // update
                $r=$wpdb->update(QUIZ_DB_TABLE_AWEBER , $data , array("id" => $id) , $dataFormat , "%d");   
                return $r !== FALSE;
            }
            
        }
      
         
  }
