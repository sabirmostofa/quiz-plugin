<?php
  class QuizMessage {
      var $code;
      var $message;
      var $data;
      var $type;
      var $isSession;
      public function QuizMessage($code, $message, $data=false, $type="error", $isSession=false) {
        
        $this->code=$code;
        $this->message=$message;
        $this->data=$data;
        $this->type=$type;
        $this->isSession=$isSession;
      } 
      public function getCode() {
        return $this->code;   
      }
      public function getMessage() {
        return $this->message;   
      }
      public function getData() {
        return $this->data;   
      }
      public function getType() {
        return $this->type;   
      }
      public function isSession() {
        return $this->isSession;   
      }
  }
  
  class QuizMessages {
      static $messages=array();
      static $registry=array();
      
      const ERROR="error";
      const NOTICE="notice";
      const WARNING="warning";
      const SUCCESS="success";
      
      const SESSION_MESSAGE_KEY="quizsessionmessages";
      
      const TYPE_ADD_QUIZ_FAIL="addquizfail";
      const TYPE_ADD_QUIZ_SUCCESS="addquizsuccess";
      const TYPE_QUIZ_NOT_EXISTS="quiznotexists";
      const TYPE_UPDATE_QUIZ_SUCCESS="updatequizsuccess";
      const TYPE_UPDATE_QUIZ_FAIL="updatequizfail";
      
      const TYPE_ADD_QUESTION_SUCCESS="addquestionsuccess";
      const TYPE_ADD_QUESTION_FAIL="addquestionfail";
      const TYPE_QUESTION_NOT_EXISTS="questionnotexists";
      const TYPE_QUESTION_UPDATE_SUCCESS="questionupdatedsuccess";
      const TYPE_QUESTION_UPDATE_FAIL="questionupdatefail";
      
      const TYPE_ADD_ANSWER_SUCCESS="addanswersuccess";
      const TYPE_ADD_ANSWER_FAIL="addanswerfail";
      const TYPE_ANSWER_NOT_EXISTS="answernotexits";
      const TYPE_ANSWER_UPDATE_FAIL="answerupdatefail";
      const TYPE_ANSWER_UPDATE_SUCCESS="answerupdatesuccess";
      
      const TYPE_SECTION_ADD_SUCCESS="sectionaddsuccess";
      const TYPE_SECTION_ADD_FAIL="sectionaddfail";
      const TYPE_SECTION_NOT_EXISTS="sectionnotexists";
      const TYPE_SECTION_UPDATE_SUCCESS="sectionupdatesuccess";
      const TYPE_SECTION_UPDATE_FAIL="sectionupdatefail";
      
      const TYPE_BADGE_ADD_SUCCESS="badgeaddsuccess";
      const TYPE_BADGE_ADD_FAIL="badgeaddfail";
      const TYPE_BADGE_NOT_EXISTS="badgenotexists";
      const TYPE_BADGE_UPDATE_SUCCESS="badgeupdatesuccess";
      const TYPE_BADGE_UPDATE_FAIL="badgeupdatefail";
      
      const TYPE_LAYOUT_UPDATE_FAIL="layoutupdatefail";
      const TYPE_LAYOUT_UPDATE_SUCCESS="layoutupdatesuccess";
      
      const TYPE_AWEBER_UPDATE_FAIL="aweberupdatefail";
      const TYPE_AWEBER_UPDATE_SUCCESS="aweberupdatesuccess";
      
      const TYPE_SEQUENCE_UPDATE_SUCCESS="sequenceupdatesuccess";
      
      const TYPE_DELETE_ANSWER="deleteanswer";
      const TYPE_DELETE_BADGE="deletebadge";
      const TYPE_DELETE_QUESTION="deletequestion";
      const TYPE_DELETE_SECTION="deletesection";
      const TYPE_DELETE_QUIZ="deletequiz";
      
      const TYPE_QUIZ_PUBLISHED="quizpublished";
      const TYPE_QUIZ_UNPUBLISHED="quizunpublished";

      const TYPE_QUIZ_DUPLICATED="quizduplicated";
      
      const TYPE_THANKYOU_UPDATE_SUCCESS="thankyouupdatesuccess";
      const TYPE_THANKYOU_UPDATE_FAIL="thankyouupdatefail";
      const TYPE_RESULT_UPDATE_SUCCESS="resultupdatesuccess";
      const TYPE_RESULT_UPDATE_FAIL="resultupdatefail";
      
      /////// FRONT END MESSAGES /////////
      const TYPE_QUIZ_SEQUENCE_NOT_FOLLOWED="quizseqnotfollowed";
      const TYPE_QUIZ_QUESTION_NOT_ANSWERED="questionnotanswered";
      const TYPE_QUIZ_OPTIN_FORM_ERROR="optinformerror";
      
      const TYPE_QUIZ_OPTION_SAVED="optionsaved";
      
      public static function init() {
          if(isset($_SESSION[self::SESSION_MESSAGE_KEY])) {
            $sd=$_SESSION[self::SESSION_MESSAGE_KEY];
            if(is_array($sd) && count($sd)) {
                foreach($sd as $msg)
                {
                    $msg=unserialize($msg);
                    self::addMessage($msg);
                }
            }   
            unset($_SESSION[self::SESSION_MESSAGE_KEY]);
          }
      }
      
      public static function addMessage($msg) {
          
        self::$messages[]=$msg;
        
        if($msg->isSession()) {
            if(!isset($_SESSION[self::SESSION_MESSAGE_KEY])) {
                $_SESSION[self::SESSION_MESSAGE_KEY]=array();
            }   
            $_SESSION[self::SESSION_MESSAGE_KEY][]=serialize($msg);
        }
        
      }   
      public static function getMessage($code) {
        foreach(self::$messages as $msg) {
            if($msg->getCode() == $code)
                return $msg; 
        }   
        return false;
      }
      public static function getAllByType($type) {
        $msgs=array();
        foreach(self::$messages as $msg) {
            if($msg->getType() == $type)
                $msgs[]=$msg;
        }   
        if(count($msgs) > 0)
            return $msgs;
        return false;                                   
      }
      public static function getAllMessages() {
        if(count(self::$messages) > 0)
            return self::$messages;
        return false;   
      }
      public static function addRegistry($key, $val) {
         self::$registry[$key]=$val;   
      }
      public static function getRegistry($key) {
         if(isset(self::$registry[$key]))
            return self::$registry[$key];
         return false; 
      }
      public static function hasRegistry($key) {
        return isset(self::$registry[$key]);   
      }
  }
  QuizMessages::init();