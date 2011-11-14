<?php
  
  class Quiz {
    
    var $id=false;
    var $title="";
    var $description="";
    var $status="p";
    var $page_id=0;
    var $include_social_links="y";
    
    var $thankyou="";
    var $thankyou_title="";
    var $thankyou2="";
    var $thankyou2_title="";
    var $result="";
    var $result_title="";
    var $thankyou_display_title="y";
    var $thankyou2_display_title="y";
    var $result_display_title="y";

    var $skip_intro="n";

    
    var $layout=false;          
    var $_layoutLoaded=false;
    var $aweber=false;
    var $_aweberLoaded=false;
    
    var $numQuestions=false;
    var $numSections=false;
    var $numBadges=false;
    var $badges=false;
    var $_badgesLoaded=false;
    var $questions=false;
    var $_questionsLoaded=false;
    var $sections=false;
    var $_sectionsLoaded=false;
    
    var $sequence=false;
    
    const PUBLISH="p";
    const DRAFT="d";
      
    public function Quiz($data = false) {
        
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
        
        if(isset($data["status"]))
            $this->status=$data["status"];
        
        if(isset($data["page_id"]))
            $this->page_id=$data["page_id"];
        
        if(isset($data["include_social_links"]))
        {
            if(is_bool($data["include_social_links"]))
                $data["include_social_links"]=$data["include_social_links"]?"y":"n";
            $this->include_social_links=$data["include_social_links"];
        }
        if(isset($data["thankyou"]))
            $this->thankyou=$data["thankyou"];
        if(isset($data["thankyou2"]))
            $this->thankyou2=$data["thankyou2"];
        if(isset($data["thankyou_title"]))
            $this->thankyou_title=$data["thankyou_title"];
        if(isset($data["thankyou2_title"]))
            $this->thankyou2_title=$data["thankyou2_title"];
        if(isset($data["thankyou_display_title"]))
            $this->thankyou_display_title=$data["thankyou_display_title"];
        if(isset($data["thankyou2_display_title"]))
            $this->thankyou2_display_title=$data["thankyou2_display_title"];
        if(isset($data["result"]))
            $this->result=$data["result"];
        if(isset($data["result_title"]))
            $this->result_title=$data["result_title"];
        if(isset($data["result_display_title"]))
            $this->result_display_title=$data["result_display_title"];
        if(isset($data["skip_intro"]))
            $this->skip_intro=$data["skip_intro"];
        
        
        return $this;
    }   
    public function loadById($id) {
        global $wpdb;
        $d=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_QUIZ . " WHERE id = $id ;" , ARRAY_A);
        if(empty($d)) {
            throw new QuizException("Quiz doesnt exist for provided id [$id]" , QuizException::NO_DATA);   
            return false;
        }
        $this->setData($d);   
        return true;
    }
    public function loadByPageId($pageid) {
        global $wpdb;
        $d=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_QUIZ . " WHERE page_id = $pageid ;" , ARRAY_A);
        if(empty($d)) {
            throw new QuizException("Quiz doesnt exist for provided page_id [$pageid]" , QuizException::NO_DATA);   
            return false;
        }
        $this->setData($d);   
        return true;
    }
    
    public function isPublished() {
        return $this->status == self::PUBLISH;
    }
    public function isDraft() {
        return $this->status == self::DRAFT;   
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
    public function getStatus() {
        return $this->status;   
    }
    public function getPageId() {
        return $this->page_id;   
    }
    public function shouldSkipIntro() {
        return $this->skip_intro == "y";
    }
    public function shouldIncludeSocialLinks() {
        return $this->include_social_links == "y";   
    }
    public function getThankyou() {
        return $this->thankyou;   
    }
    public function getThankyouTitle() {
        return $this->thankyou_title;    
    }
    public function shouldDisplayThankyouTitle() {
        return $this->thankyou_display_title == "y";
    }
    public function getThankyou2() {
        return $this->thankyou2;   
    }
    public function getThankyou2Title() {
        return $this->thankyou2_title;    
    }
    public function shouldDisplayThankyou2Title() {
        return $this->thankyou2_display_title == "y";
    }
    public function getResult() {
        return $this->result;   
    }
    public function getResultTitle() {
        return $this->result_title;    
    }
    public function shouldDisplayResultTitle() {
        return $this->result_display_title == "y";
    }
    public function getLayout() {
        if($this->_layoutLoaded === FALSE) {
            $layout=false;
            try {
               $layout=new QuizLayout();
               $layout->loadByQuizId($this->getId()); 
            }   
            catch(QuizException $qe) {
                $layout=false;   
            }
            $this->layout=$layout;
            $this->_layoutLoaded=true;
        }
        return $this->layout;
    }
    public function getAweber() {
        if($this->_aweberLoaded === FALSE) {
            $aweber=false;
            try {
               $aweber=new QuizAweber();
               $aweber->loadByQuizId($this->getId()); 
            }   
            catch(QuizException $qe) {
                $aweber=false;   
            }
            $this->aweber=$aweber;
            $this->_aweberLoaded=true;
        }
        return $this->aweber;
    }
    public function getNumQuestions() {
        global $wpdb;
        if($this->numQuestions === FALSE) {
            $this->numQuestions = $wpdb->get_var("SELECT COUNT(id) as c FROM " . QUIZ_DB_TABLE_QUESTIONS . " where quiz_id = " . $this->getId() . ";");
        }   
        return $this->numQuestions;
    }
    public function getNumBadges() {
        global $wpdb;
        if($this->numBadges === FALSE) {
            $this->numBadges = $wpdb->get_var("SELECT COUNT(id) as c FROM " . QUIZ_DB_TABLE_BADGES . " where quiz_id = " . $this->getId() . ";");
        }   
        return $this->numBadges;
    }
    public function getNumSections() {
        global $wpdb;
        if($this->numSections === FALSE) {
            $this->numSections = $wpdb->get_var("SELECT COUNT(id) as c FROM " . QUIZ_DB_TABLE_SECTIONS . " where quiz_id = " . $this->getId() . ";");
        }   
        return $this->numSections;
    }
    public function getCurrentDisplayOrder() {
        global $wpdb;
        $qMax=$wpdb->get_var("SELECT MAX(display_order) as max FROM " . QUIZ_DB_TABLE_QUESTIONS . " WHERE quiz_id = " . $this->getId() . ";");   
        $sMax=$wpdb->get_var("SELECT MAX(display_order) as max FROM " . QUIZ_DB_TABLE_SECTIONS . " WHERE quiz_id = " . $this->getId() . ";");   
        $qMax=intval($qMax);
        $sMax=intval($sMax);
        $max=max($qMax, $sMax);
        return $max+1;
    }
    public function getValidBadgeRange() {
        $questions=$this->getQuestions();
        $range=array("min" =>0, "max" => 0);
        if($questions) {
            foreach($questions as $question) {
                $answers=$question->getAnswers();
                if($answers) {
                    $values=array();
                    $svalue=0;
                    foreach($answers as $answer) {
                        $values[]=$answer->getValue();
                        $svalue+= $answer->getValue();
                    }               
                    if($question->isSingleAnswerType()) {
                        sort($values);
                        $min=intval($values[0]);
                        $max=intval($values[count($values)-1]);
                        $range["min"]+=$min;
                        $range["max"]+=$max;
                    }
                    else {
                        
                        $range["max"]+=$svalue;   
                    }
                }   
            }
        }   
        return $range;
    }
    public function getBadges() {
        global $wpdb;
        if($this->_badgesLoaded === FALSE) {
            $d=$wpdb->get_results("SELECT * FROM " . QUIZ_DB_TABLE_BADGES . " WHERE quiz_id = " . $this->getId());
            if($d) {
                $this->badges=array();
                foreach($d as $bd) {
                    $this->badges[]=new QuizBadge($bd);
                }   
            }
            $this->_badgesLoaded=true;
        }   
        return $this->badges;
    }
    public function getQuestions() {
        global $wpdb;
        if($this->_questionsLoaded === FALSE) {
            $d=$wpdb->get_results("SELECT * FROM " . QUIZ_DB_TABLE_QUESTIONS . " WHERE quiz_id = " . $this->getId() . " ORDER BY display_order ASC");
            if($d) {
                $this->questions=array();
                foreach($d as $bd) {
                    $this->questions[]=new QuizQuestion($bd);
                }   
            }
            $this->_questionsLoaded=true;
        }   
        return $this->questions;
    }
    public function getSections() {
        global $wpdb;
        if($this->_sectionsLoaded === FALSE) {
            $d=$wpdb->get_results("SELECT * FROM " . QUIZ_DB_TABLE_SECTIONS . " WHERE quiz_id = " . $this->getId() . " ORDER BY display_order ASC");
            if($d) {
                $this->sections=array();
                foreach($d as $bd) {
                    $this->sections[]=new QuizSection($bd);
                }   
            }
            $this->_sectionsLoaded=true;
        }   
        return $this->sections;
    }
    public function getSequence() {
        if($this->sequence === FALSE) {
            $d=array();
            $questions=$this->getQuestions();
            $sections=$this->getSections();
            if(is_array($questions))
                $d=array_merge($d, $questions);
            if(is_array($sections))
                $d=array_merge($d, $sections);
            usort($d, "quiz_sequence_sort");
            $this->sequence=$d;
        }
        return $this->sequence;   
    }
    // this function load question from its questions list, using because if we have already loaded uestion then we can just it from this list
    public function getQuestionById($qid) {
        $questions=$this->getQuestions();
        if($questions) {
            foreach($questions as $q) {
                if($q->getId() == $qid) {
                    return $q;
                }   
            }
        }   
        return false;
    }
    public function getDisplayOrderByObject($obj) {
        $sequence=$this->getSequence();
        if($sequence) {
            $d=1;
            foreach($sequence as $sobj) {
                if( ($sobj->getId() == $obj->getId()) && (get_class($sobj) == get_class($obj)) ) {
                   return $d; 
                }
                $d++;   
            }
        }   
        return false;
    }
    public function getObjectAtDisplayOrder($displayOrder) {
        $sequence=$this->getSequence();
        if(isset($sequence[$displayOrder-1])) {
            return $sequence[$displayOrder-1];
        }   
        return false;
    }
    public function delete() {
        global $wpdb;
        
        $id=$this->getId();
        if(empty($id))
            return false;
            
        // delete all question
        $questions=$this->getQuestions();
        if($questions) {
            foreach($questions as $question) {
                $question->delete();
            }   
        }
        // delete all sections
        $sections=$this->getSections();
        if($sections) {
            foreach($sections as $section) {
                $section->delete();
            }   
        }
        // delete all badges
        $badges=$this->getBadges();
        if($badges) {
            foreach($badges as $badge) {
                $badge->delete();
            }   
        }
        // delete layout
        $layout=$this->getLayout();
        if($layout)
            $layout->delete();
        // delete aweber details
        $aweber=$this->getAweber();
        if($aweber)
            $aweber->delete();
        // delete quiz
        $wpdb->query("DELETE FROM " . QUIZ_DB_TABLE_QUIZ . " WHERE id = $id ;");
           
    }
    public function save() {
        global $wpdb;
        $id=$this->getId();
        $d=array(
            "id" => $id, 
            "title" => $this->getTitle(), 
            "description" => $this->getDescription(), 
            "status" => $this->getStatus(), 
            "page_id" => $this->getPageId(), 
            "include_social_links" => $this->include_social_links , 
            "thankyou" => $this->getThankyou(),
            "thankyou2" => $this->getThankyou2(),
            "thankyou_title" => $this->getThankyouTitle(),
            "thankyou2_title" => $this->getThankyou2Title(),
            "thankyou_display_title" => $this->thankyou_display_title,
            "thankyou2_display_title" => $this->thankyou2_display_title,
            "result" => $this->getResult(),
            "result_title" => $this->getResultTitle(),
            "result_display_title" => $this->result_display_title,
            "skip_intro" => $this->skip_intro
        );
        $df=array("%d" , "%s" , "%s" , "%s", "%d", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s", "%s");
        if(empty($id)) {
            // insert
            unset($d["id"]);
            array_shift($df);
            $r=$wpdb->insert(QUIZ_DB_TABLE_QUIZ , $d , $df);   
            if($r) {
                $id=$wpdb->insert_id;
                $this->setData("id" , $id);   
            }
            return $r !== FALSE;
        }   
        else {
            // update
            $r=$wpdb->update(QUIZ_DB_TABLE_QUIZ , $d , array("id" => $id) , $df , "%d");   
            return $r !== FALSE;
        }
        
    }
  }