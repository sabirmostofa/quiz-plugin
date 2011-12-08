<?php
  class QuizFront {
    
      static $quiz=false;
      static $task=false;
      static $post=false;
      
      static $question=false;
      static $section=false;
      static $currentDisplayOrder=false;
      static $resultKey=false;
      static $resultBadgeMD=false;
      static $resultLink="";
      
      static $title="Quiz";
      static $content="Quiz";
      static $displayTitle=true;
      static $sessionArray=array();
      static $useAweber=true;
      static $aweberResultData=false;
      
      const REQUEST_QUESTION='question';
      const REQUEST_SECTION='section';
      const REQUEST_OPTIN='optin';
      const REQUEST_THANKYOU='thankyou';
      const REQUEST_RESULT='result';
      
      const TASK_SHOWQUESTION='showQuestion';
      const TASK_SHOWSECTION='showSection';
      const TASK_SHOWOPTIN='showOptin';
      const TASK_SHOWTHANKYOU='showThankyou';
      const TASK_SHOWRESULT='showResult';
      const TASK_SHOWINTRO="showIntro";
      const FORMTASK_HANDLEQUESTION="handlequestion";
      const FORMTASK_HANDLEOPTIN="handleoptin";
      
      public static function setQuiz($quiz) {
           self::$quiz=$quiz;
      }
      public static function getQuiz($quiz) {
           return self::$quiz;
      }
      public static function handleRequest() {
          // start session
          @session_start();
          
          ob_start();
          
          // add content action
          add_action("the_content", array("QuizFront", "handleContentAction"));
          add_action("the_title" , array("QuizFront", "handleTitleAction"));
          wp_register_style("quizcss" , QUIZ_URL . "/css/quiz.css");
          wp_enqueue_style("quizcss");
          
          wp_enqueue_script('jquery');
          
          self::processRequest();
          
          add_action("wp_head" , array("QuizFront", "handleWpHead"));
          
      }   
      public static function handleWpHead() {
          ?>
          <!-- QUIZ_WP_HEAD_REPLACE -->
          <?php
      }
      public static function releaseBuffer() {
         $tillNow=ob_get_clean();
         $c=preg_replace('/<title>.*?<\/title>/' , '<title>' . self::getTitle() . '</title>' , $tillNow);   
         
         $wphead="";

         // replace if canonical url tag present
         $chref=  quiz_self_url();
         $chrefE=substr($chref, strlen($chref)-1 , 1);
         if($chrefE != '/')
             $chref .= '/';
         $c = preg_replace('/<link(\s+)rel=[\'"]canonical[\'"][^>]+>/m', '', $c);
         $wphead .= '<link rel="canonical" href="' . $chref . '" />';

         if(self::$task == self::TASK_SHOWRESULT) {
             $md="";
             if(self::$resultBadgeMD != "") {
                $md=self::$resultBadgeMD;
             }
              $wphead .='
                   <meta property="fb:admins" content="1674739378"/>
                   <meta property="og:title" content="' . self::$title. '" />
                   <meta property="og:type" content="blog" />
                   <meta property="og:url" content="' . self::$resultLink. '"/>
                   <meta name="description" content="' . $md . '" />

              ';
              
         }
         $c=str_replace('<!-- QUIZ_WP_HEAD_REPLACE -->' , $wphead, $c); 

         
         
         echo $c;
      }
      public static function handleContentAction($content) {
          self::releaseBuffer();
          return self::getContent();
      }
      public static function handleTitleAction($title, $id=false) {
          if(!self::$displayTitle) {
              $ktitle="<span id='quiz-del-title'></span>";   
          }
          else 
              $ktitle=self::$title;
          if(in_the_loop())
            return $ktitle; //self::getTitle();     
          return $title;
      }
      public static function processRequest() {
          self::parseRequest();   
          if(self::$task == self::TASK_SHOWQUESTION || self::TASK_SHOWSECTION) {
            if(self::$currentDisplayOrder == 1) {
                unset($_SESSION["quizresultid" . self::$quiz->getId()]);
            }   
          }
          self::handleForm();
          $fn=self::$task;
          ob_start();
          self::$fn();  //task functions must set the title, and render all content (which will be collected by the buffer)
          $taskResponse=ob_get_clean();
          
          // show any messages
          $taskResponse=quiz_render_messages(false) . $taskResponse;
          
          self::$content=self::layoutContent($taskResponse);
          if(!self::$displayTitle) {
            $c='
              <script type="text/javascript">
                jQuery(document).ready(function() {
                   var delSpan=jQuery("#quiz-del-title");
                   var parentSpan=delSpan.parent().get(0);
                   var parentTag=parentSpan.tagName;
                   if(parentTag == "A") {
                        var parentParent=parentSpan.parent().get(0);
                        if(parentParent.tagName.charAt(0) == "H") {
                            jQuery(parentParent).remove();
                        }
                        else {
                            jQuery(parentSpan).remove();
                        }
                   }
                   else {
                        jQuery(parentSpan).remove();
                   }
                   
                });
              </script>
            '; 
            self::$content .= $c;  
          }
      }
      public function layoutContent($content) {
          $layout=self::$quiz->getLayout();
          $useAtQuestion=true;
           $useAtSection=true;
           $useAtOptin=true;
           $useAtResult=true;
           $header_content="";
           $left_content="";
           $right_content="";
           $bottom_content="";
           
           if($layout) {
                $useAtQuestion=$layout->shouldUseAtQuestion();   
                $useAtSection=$layout->shouldUseAtSection();   
                $useAtOptin=$layout->shouldUseAtOptin();   
                $useAtResult=$layout->shouldUseAtResult();   
                $header_content= $layout->getHeaderContent();
                $left_content= $layout->getLeftContent();
                $right_content= $layout->getRightContent();
                $bottom_content= $layout->getBottomContent();
                $header_content=wysiwyg_db_to_html($header_content);
                $left_content=wysiwyg_db_to_html($left_content);
                $right_content=wysiwyg_db_to_html($right_content);
                $bottom_content=wysiwyg_db_to_html($bottom_content);
           }
          $useLayout=false; 
          if(self::$task == self::TASK_SHOWQUESTION) {
              if($useAtQuestion) {
                 $useLayout=true;
              } 
          }
          if(self::$task == self::TASK_SHOWSECTION) {
              if($useAtSection) {
                 $useLayout=true;
              } 
          }
          if(self::$task == self::TASK_SHOWOPTIN) {
              if($useAtOptin) {
                 $useLayout=true;
              } 
          }
          if(self::$task == self::TASK_SHOWTHANKYOU) {
              if($useAtOptin) {
                 $useLayout=true;
              } 
          }
          if(self::$task == self::TASK_SHOWRESULT) {
              if($useAtResult) {
                 $useLayout=true;
              } 
          }
          
          if($useLayout) {
             $c='<table class="quiz-layout-table"><tr><td colspan="3" valign="top" align="left">' . $header_content . '</td></tr><tr><td valign="top" align="left">' . $left_content . '</td><td valign="top" align="left">' . $content . '</td><td valign="top" align="left">' . $right_content . '</td></tr><tr><td colspan="3" valign="top" align="left">' . $bottom_content . '</td></tr></table>';  
          }
          else {
             $c=$content; 
          }
          return '<div class="quiz-main">' . $c . '</div>';
      }
      public static function handleForm() {
          global $wpdb;
          if(!isset($_POST["formtask"])) {
              return;   
          }
          $formtask=$_POST["formtask"];
          if($formtask == self::FORMTASK_HANDLEQUESTION) {
            $oldquestionid=intval($_POST["oldquestionid"]);   
            $oldquestion=self::$quiz->getQuestionById($oldquestionid);
            $sequence=self::$quiz->getSequence();
            
            $cont=false;
            $prevQuestionIds=array();
            if($sequence) {
                foreach($sequence as $seq) {
                    if($cont)
                        continue;
                    if(get_class($seq)=="QuizQuestion") {
                        if($seq->getId() == $oldquestionid) 
                            $cont=true;
                        else
                            $prevQuestionIds[]=$seq->getId();
                    }
                    
                }   
            }
            
            
            // check if old data exist
            if(!self::hasResultData($prevQuestionIds)) {
                // redirect
                QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUIZ_SEQUENCE_NOT_FOLLOWED , __("Please follow quiz sequence") , false , QuizMessages::ERROR , true));
                wp_redirect(get_permalink(self::$quiz->getPageId()));
                die();      
            }
            
            $singleAnswer=$oldquestion->isSingleAnswerType();
            $userAnswers=array();
            $userAnswerValid=false;
            
            if($singleAnswer) {
                // only one answer with name answer
                $v="";
                if(isset($_POST["answer"]))
                    $v=$_POST["answer"];
                $v=intval(str_replace("y", "" , $v));
                $userAnswers[]=$v; 
                $userAnswerValid=!empty($v); // single answer must always be answered  
            }
            else {
                // can be many answers
                $answers=$oldquestion->getAnswers();
                foreach($answers as $answer) {
                    $answerId=$answer->getId();
                    if(isset($_POST["answer" . $answerId]) && $_POST["answer" . $answerId] == "y" . $answerId) {
                        $userAnswers[]=$answerId;   
                    }
                        
                }   
                $userAnswerValid=true; // multiple answer can be empty
            }
            
            if($userAnswerValid) {
                self::addResultData($oldquestionid , $userAnswers);
                
                
            }
            else {
                QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUIZ_QUESTION_NOT_ANSWERED , __("Questions not answered properly. Please answer it again") , $oldquestionid , QuizMessages::ERROR , true));
                $porder=self::$quiz->getDisplayOrderByObject($oldquestion);
                if($porder) {
                   $porder=intval($porder); 
                   $porder=$porder < 10 ? ("0" . $porder) : $porder;
                   $oldquestionurl=get_permalink(self::$quiz->getPageId()) . $porder . "/question";      
                }
                else {
                   $oldquestionurl=get_permalink(self::$quiz->getPageId()); 
                }
                wp_redirect($oldquestionurl);
                die();   
            }
            return;
          }
          ///////////////// Handled question
          
          if($formtask  == self::FORMTASK_HANDLEOPTIN) {
            
              // check if sequence was maintained
              $questions=self::$quiz->getQuestions();
              $qids=array();
              if($questions) {
                  foreach($questions as $question) 
                    $qids[]=$question->getId();
              }
              if(!self::hasResultData($qids)) {
                  // redirect
                    QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUIZ_SEQUENCE_NOT_FOLLOWED , __("Please follow quiz sequence") , false , QuizMessages::ERROR , true));
                    wp_redirect(get_permalink(self::$quiz->getPageId()));
                    die();  
              }
              
              // check if form is submitted correctly
              $firstname=trim($_POST["firstname"]);
              $lastname=trim($_POST["lastname"]);
              $email=trim($_POST["email"]);
              
              if(empty($firstname) || empty($email)) {
                   QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUIZ_OPTIN_FORM_ERROR , __("Please fill out the form properly") , false , QuizMessages::ERROR , true));
                    wp_redirect(get_permalink(self::$quiz->getPageId()) . self::REQUEST_OPTIN);
                    die();
              }
              $resultkey=self::getResultKey();
              // finalize result
              $d=array(
                "quiz_id" => self::$quiz->getId(),
                "status" => "c",
                "firstname" => $firstname,
                "lastname" => $lastname,
                "email" => $email,
                "resultkey" =>  $resultkey
            );
               $resId=$_SESSION["quizresultid" . self::$quiz->getId()];
               $wpdb->update(QUIZ_DB_TABLE_RESULTS, $d, array("id" => $resId) , array("%d", "%s", "%s", "%s" , "%s", "%s"), "%d");
               $d['id']=$resId;
               self::$aweberResultData=$d;
               // check if we should to submit to aweber
               self::$useAweber=true;
               $aweber=self::$quiz->getAweber();   
               // get all quiz ids where this email was used
               $quizIds=$wpdb->get_results("SELECT quiz_id FROM " . QUIZ_DB_TABLE_RESULTS . " WHERE email = '$email' AND id != $resId");
               if($quizIds) {
                    foreach($quizIds as $qid) {
                        if(!self::$useAweber)
                            continue;
                        // get all listnames for these quizids
                        $listname=$wpdb->get_var("SELECT listname FROM " . QUIZ_DB_TABLE_AWEBER . " where quiz_id = {$qid->quiz_id}");
                        if($listname && $listname == $aweber->getListname())
                            self::$useAweber=false;
                    }   
               }
               /////////////////////////////////////////
               if(self::$useAweber) {
                  // submit to aweber
                  
                  $aweberUrl='http://www.aweber.com/scripts/addlead.pl';
                  $post_data=array(
                     "meta_split_id" => "",
                     "listname" => $aweber->getListname(),
                     "meta_message" => "1",
                     "meta_required" => "name (awf_first),email",
                     "name (awf_first)" => $firstname,
                     "name (awf_last)" => $lastname,
                     "email" => $email,
                     "custom resultlink" => get_permalink(self::$quiz->getPageId()) . "result/" . self::$aweberResultData['id'],
                     "submit" => "Submit"
                  );
                  
                  
                
                //traverse array and prepare data for posting (key1=value1)
                foreach ( $post_data as $key => $value) {
                    $post_items[] = $key . '=' . $value;
                }
                
                //create the final string to be posted using implode()
                $post_string = implode ('&', $post_items);
                
                //create cURL connection
                $curl_connection = curl_init($aweberUrl);
                
                //set options
                curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
                curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
                
                //set data to be posted
                curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
                
                //perform our request
                $result = curl_exec($curl_connection);
                
                $curlErrorNo=curl_errno($curl_connection);
                $curlError=curl_error($curl_connection);
                
                //close the connection
                curl_close($curl_connection);
                if(!empty($curlError)) {
                     QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUIZ_OPTIN_FORM_ERROR , "Error submitting to aweber. Error No : $curlErrorNo Error : $curlError" , false , QuizMessages::ERROR , true));
                    wp_redirect(get_permalink(self::$quiz->getPageId()) . self::REQUEST_OPTIN);
                    die();
                }
            }
            else {
                // dont use aweber just display thankyou2    
                
            }
            
            
            
            
            
               
          }
      }
      public static function getBadgeForResult($resultId) {
          global $wpdb;
          /* @var $wpdb WpDb */
          // check if this result already has a calculated badge in result table, if yes then return the badge
          $result=$wpdb->get_row('SELECT * FROM ' . QUIZ_DB_TABLE_RESULTS . ' WHERE id = ' . $resultId);
          if(!$result)
              return false;
          if($result->badge_id) {
              $badge=new QuizBadge();
              try {
                  $badge->loadById($result->badge_id);
                  return $badge;
              }
              catch(QuizException $qe) {
                  
              }
          }
          
          // first get total points scored
          $resultData=$wpdb->get_results("SELECT * FROM " . QUIZ_DB_TABLE_RESULT_DATA . " WHERE result_id = $resultId ");
          if(!$resultData)
            return false;

          $sequence=self::$quiz->getSequence();
          
          // see if scored any badge
          $totalResultScore=0;
          $resultArray=array();
          foreach($sequence as $seq) {
             $seqId=$seq->getId();
             $isQuestion=get_class($seq) == "QuizQuestion";
             if($isQuestion) {
                  $answers=$seq->getAnswers();
                  if($answers) {
                    foreach($answers as $answer) {
                        $answerId=$answer->getId();
                        foreach($resultData as $rd) {
                            if($rd->answer_id == $answerId) {
                                $totalResultScore+=intval($answer->getValue());
                            }
                        }
                    }
                  }
             }
          }

          $resultBadge=false;

          $badges=self::$quiz->getBadges();
          $randomBadges=array();
          if($badges){
             foreach($badges as $badge) {
                 if($badge->isRandom()) {
                    $randomBadges[]=$badge;
                 }
                 else {
                    if($badge->isValueInRange($totalResultScore))
                        $resultBadge=$badge;
                 }
             }
          }

          // if no related badge found, then choose badge randomly if possible
          if($resultBadge === FALSE) {
            if(count($randomBadges)) {
                $rv=rand(0, count($randomBadges)-1);
                if(isset($randomBadges[$rv]))
                    $resultBadge=$randomBadges[$rv];
            }
          }
          // store this badge to the result table
          if($resultBadge) {
              $wpdb->update(QUIZ_DB_TABLE_RESULTS, array('badge_id' => $resultBadge->id), array('id' => $resultId), "%d", "%d");
          }
          // return badge
          return $resultBadge;
      }
      public static function parseRequest() {
          global $wp, $wp_query;
          // buffer has been released at this point
          
          // first decide upon task
          // task can be show-question, show-section, handle-question-submit, show-optin, show-thankyou, show-result
          self::$post=$post=$wp_query->get_queried_object();
          $req_uri=$_SERVER['REQUEST_URI'];   
          
          // check for question
          $s='/\/(\d+)\/' . self::REQUEST_QUESTION .'/';
          if(preg_match($s, $req_uri, $matches)) {
             $v=intval($matches[1]);
             $obj=self::$quiz->getObjectAtDisplayOrder($v);
             if(!$obj)
                return false;
             if(get_class($obj) == "QuizQuestion") {
                self::$task=self::TASK_SHOWQUESTION;
                self::$question=$obj;   
             }
             else {
                self::$task=self::TASK_SHOWSECTION;
                self::$section=$obj;    
             }
             self::$currentDisplayOrder=$v;
             return true;
          }
          
          $s='/\/(\d+)\/' . self::REQUEST_SECTION .'/';
          if(preg_match($s, $req_uri, $matches)) {
             $v=intval($matches[1]);
             $obj=self::$quiz->getObjectAtDisplayOrder($v);
             if(!$obj)
                return false;
             if(get_class($obj) == "QuizQuestion") {
                self::$task=self::TASK_SHOWQUESTION;
                self::$question=$obj;   
             }
             else {
                self::$task=self::TASK_SHOWSECTION;
                self::$section=$obj;    
             }
             self::$currentDisplayOrder=$v;
             return true;
          }
          
          
          // check for optin
          $s='/' . $post->post_name . '/' . self::REQUEST_OPTIN;
          $pos=stripos($req_uri , $s);
          if($pos !== FALSE) {
              self::$task=self::TASK_SHOWOPTIN;
              return true;
          }
          
          // check for thankyou
          $s='/' . $post->post_name . '/' . self::REQUEST_THANKYOU;
          $pos=stripos($req_uri , $s);
          if($pos !== FALSE) {
              self::$task=self::TASK_SHOWTHANKYOU;
              return true;
          }
          
          // check for result
          $s='/' . $post->post_name . '/' . self::REQUEST_RESULT . '/';
          $pos=stripos($req_uri , $s);
          if($pos !== FALSE) {
              $s2=substr($req_uri, $pos);
              $v=str_replace($s, "", $s2);
              self::$task=self::TASK_SHOWRESULT;
              self::$resultKey=$v;
              //$v => result key 
              return true;
          }
          
          self::$task=self::TASK_SHOWINTRO;
          self::$currentDisplayOrder=0;
          
          // if quiz has skip intro set, then move on to the first display object
          if(self::$quiz->shouldSkipIntro()) {
              $sequence=self::$quiz->getSequence();
              $seqLen=count($sequence);
              $nextAction=false;
              /*
              * Notice: currentDisplayOrder (displayorder starts at 1 not 0)
              */
              if(self::$task == self::TASK_SHOWINTRO) {
                   if($seqLen > self::$currentDisplayOrder) {
                       // has next object in sequence
                       $obj=$sequence[self::$currentDisplayOrder];
                       $pdorder=self::$currentDisplayOrder +1;
                       if($pdorder <10)
                          $pdorder="0" . $pdorder;
                       if(get_class($obj) == "QuizQuestion") {
                          self::$task=self::TASK_SHOWQUESTION;
                          self::$question=$obj;
                       }
                       else {
                          self::$task=self::TASK_SHOWSECTION;
                          self::$section=$obj;
                       }
                     self::$currentDisplayOrder=intval($pdorder);
                     return true;

                   }
                   else {
                        
                   }
              }

          }

 
          
          return true;
      }
      public static function handleTemplateRedirectAction() {
          global $wp_query;
          $wp_query->is_404=false;
          $wp_query->page=true;   
      }
      public static function getTitle() {
          return self::$title;
      }
      public static function getContent() {
          return self::$content;
      }
      ///////////////////
      public static function showIntro() {
           unset($_SESSION["quizresultid"  . self::$quiz->getId()]);
           self::$title=textarea_db_to_html(self::$quiz->getTitle());
           require self::getViewsFolder() . "/showintro.php";
      }
      public static function showQuestion() {
          self::$title=textarea_db_to_html(self::processString(self::$question->getTitle() , array("{quiz-title}" => self::$quiz->getTitle())));
          self::$displayTitle=self::$question->shouldDisplayTitle();
          require self::getViewsFolder() . '/showquestion.php';    
      }
      public static function showSection() {
          self::$title=textarea_db_to_html(self::processString(self::$section->getTitle() , array("{quiz-title}" => self::$quiz->getTitle())));
          self::$displayTitle=self::$section->shouldDisplayTitle();
          require self::getViewsFolder() . '/showsection.php';
      }
      public static function showThankyou() {
          global $wpdb;
          // aweber may send data, obtain it
          $afields=array(
              'firstname' => 'name_(awf_first)',
              'lastname' => 'name_(awf_last)',
              'email' => 'email',
              'result-link' => 'custom_resultlink',
              'cfield' => 'custom_cfield'
          );
          $adata=array();
          foreach($afields as $ai => $av) {
              if(isset($_GET[$av])) {
                  $adata[$ai]=urldecode($_GET[$av]);
              }
              else {
                  $adata[$ai]="";
              }
          }

          // if we get aweber data, then store it in database
          // finalize result
          if($adata["result-link"]) {
              $rlink=$adata["result-link"];
              $rid=str_replace(get_permalink(self::$quiz->getPageId()) . "result/" , "", $rlink);
              $rid=str_replace("/", "", $rid);
              $rid=intval($rid);
              $d=array(
                "status" => "c",
                "firstname" => $adata["firstname"],
                "lastname" => $adata["lastname"],
                "email" => $adata["email"],
                "cfield" => $adata["cfield"]
            );
              if($rid) {
                  $wpdb->update(QUIZ_DB_TABLE_RESULTS, $d, array('id' => $rid), "%s", "%d");
              }
          }
          $thankyouReplace=array(
            "{quiz-title}" => self::$quiz->getTitle(), 
            "{first-name}" => $adata["firstname"],
            "{last-name}" => $adata["lastname"],
            "{email}" => $adata["email"],
            "{result-link}" =>  $adata["result-link"],
              "{custom-field}" => $adata["cfield"]
          );


          if(!isset($_GET["onlist"])) {
             self::$title=textarea_db_to_html(self::processString(self::$quiz->getThankyouTitle() , $thankyouReplace));
             self::$displayTitle=self::$quiz->shouldDisplayThankyouTitle();
             $thankyoucontent=self::processString(self::$quiz->getThankyou() , $thankyouReplace);
          }
          else {
             self::$title=textarea_db_to_html(self::processString(self::$quiz->getThankyou2Title() , $thankyouReplace));
             self::$displayTitle=self::$quiz->shouldDisplayThankyou2Title();
             $thankyoucontent=self::processString(self::$quiz->getThankyou2() , $thankyouReplace);

          }
          
          require self::getViewsFolder() . '/showthankyou.php';
		  
	  unset($_SESSION["quizresultid" . self::$quiz->getId()]);
      }
      public static function showOptin() {
          global $wpdb;

          // check if sequence was maintained
          $questions=self::$quiz->getQuestions();
          $qids=array();
          if($questions) {
              foreach($questions as $question)
                $qids[]=$question->getId();
          }
          if(!self::hasResultData($qids)) {
              // redirect
                QuizMessages::addMessage(new QuizMessage(QuizMessages::TYPE_QUIZ_SEQUENCE_NOT_FOLLOWED , __("Please follow quiz sequence") , false , QuizMessages::ERROR , true));
                wp_redirect(get_permalink(self::$quiz->getPageId()));
                die();
          }

          $resultkey=self::getResultKey();
          // finalize result
          $d=array(
            "quiz_id" => self::$quiz->getId(),
            "status" => "c",
            "firstname" => "",
            "lastname" => "",
            "email" => "",
            "resultkey" =>  $resultkey
        );
           $resId=$_SESSION["quizresultid" . self::$quiz->getId()];
           $wpdb->update(QUIZ_DB_TABLE_RESULTS, $d, array("id" => $resId) , array("%d", "%s", "%s", "%s" , "%s", "%s"), "%d");

          $resultLink=get_permalink(self::$quiz->getPageId()) . "result/" . $resId;

          $aweber=self::$quiz->getAweber();
          $skipOptin=true;
          if($aweber) {
            if(!$aweber->shouldSkipOptin()) {
               $skipOptin=false;
            }
            self::$title=textarea_db_to_html(self::processString($aweber->getTitle() , array("{quiz-title}" => self::$quiz->getTitle())));
            self::$displayTitle=$aweber->shouldDisplayTitle();
          }
          require self::getViewsFolder() . '/showoptin.php';
          
      }
      public static function showResult() {
          global $wpdb;
          self::$title=textarea_db_to_html(self::$quiz->getTitle()) . ' - Result'; 
          $resultKey=self::$resultKey;
          $resultKey=str_replace('/', '' , $resultKey);
          
          $result=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_RESULTS . " WHERE id = '$resultKey' ");
          if(!$result) {
            $result=$wpdb->get_row("SELECT * FROM " . QUIZ_DB_TABLE_RESULTS . " WHERE resultkey = '$resultKey' ");
            if(!$result)
                return;
          }
            
          $resultId=$result->id;
          
          $resultData=$wpdb->get_results("SELECT * FROM " . QUIZ_DB_TABLE_RESULT_DATA . " WHERE result_id = $resultId ");
          if(!$resultData)
            return;
          
          $sequence=self::$quiz->getSequence();
          $showShareLinks=self::$quiz->shouldIncludeSocialLinks();
          
          // see if scored any badge
          $totalResultScore=0;
          $resultArray=array();
          foreach($sequence as $seq) {
             $seqId=$seq->getId();
             $isQuestion=get_class($seq) == "QuizQuestion";
             if($isQuestion) {
                  $answers=$seq->getAnswers();
                  if($answers) {
                    foreach($answers as $answer) {
                        $answerId=$answer->getId();
                        foreach($resultData as $rd) {
                            if($rd->answer_id == $answerId) {
                                $totalResultScore+=intval($answer->getValue());
                                $resultArray[]=array("type" => "question", "value" => $answer->getValue(), "response" => $answer->getResponse());
                            }   
                        }
                    }   
                  }  
             }
             else {
                 if($seq->shouldDisplayTitleAtResult()) {
                     $resultArray[]=array("type" => "section" , "title" => self::processString($seq->getTitle(), array("{quiz-title}" => self::$quiz->getTitle())));
                     
                 }
                 
             }   
          }

          $resultBadge=false;
          /****** Now, we have a function to get tha badge ****
          $badges=self::$quiz->getBadges();
          if($badges){
             foreach($badges as $badge) {
                 if($badge->isValueInRange($totalResultScore)) 
                    $resultBadge=$badge;  
             }
          }
           *
           */
          $resultBadge=self::getBadgeForResult($resultId);
          $resultBadgeTitle="";
          if($resultBadge)
              $resultBadgeTitle=$resultBadge->getMetaTitle();

          if(!current_user_can( 'manage_options' )) {
            // update views
            $sql="UPDATE " . QUIZ_DB_TABLE_RESULTS . " SET numviews = numviews + 1 WHERE id = $resultId";   
            $wpdb->query($sql);
          }
          $resultLink=get_permalink(self::$quiz->getPageId()) . "result/" . $resultId;
          self::$resultLink=$resultLink;
          $resultReplace=array(
            "{quiz-title}" => self::$quiz->getTitle(), 
            "{first-name}" => $result->firstname, 
            "{last-name}" => $result->lastname, 
            "{email}" => $result->email, 
            "{result-link}" => $resultLink ,
            "{numviews}" => $result->numviews,
            "{totalscore}" => $totalResultScore,
              "{badge-title}" => $resultBadgeTitle,
              "{custom-field}" => $result->cfield
          );
          $rbd="";
          if($resultBadge)
              $rbd=$resultBadge->getMetaDescription();
          self::$resultBadgeMD=db_to_textfield(self::processString($rbd, $resultReplace));
          self::$title=textarea_db_to_html(self::processString(self::$quiz->getResultTitle() , $resultReplace));
          self::$displayTitle=self::$quiz->shouldDisplayResultTitle();  
          $resultContent=self::processString(self::$quiz->getResult() , $resultReplace);
          
          wp_enqueue_script( 'FB-Loader', 'http://static.ak.fbcdn.net/connect.php/js/FB.Loader', array(), 322597, true );
          wp_enqueue_script( 'FB-Share', 'http://static.ak.fbcdn.net/connect.php/js/FB.Share', array( 'FB-Loader' ), 322597, true );
          
          require self::getViewsFolder() . '/showresult.php'; 
      }
      public static function getViewsFolder() {
        return apply_filters("quiz_views_folder", QUIZ_PATH . '/front/views');   
      }
      public static function getNextAction() {
          $sequence=self::$quiz->getSequence();
          $seqLen=count($sequence);
          $nextAction=false;
          /*
          * Notice: currentDisplayOrder (displayorder starts at 1 not 0)
          */
          if(self::$task == self::TASK_SHOWQUESTION || self::$task == self::TASK_SHOWSECTION || self::$task == self::TASK_SHOWINTRO) {
               if($seqLen > self::$currentDisplayOrder) {
                   // has next object in sequence 
                   $nextObj=$sequence[self::$currentDisplayOrder];   
                   $pdorder=self::$currentDisplayOrder +1;
                   if($pdorder <10)
                      $pdorder="0" . $pdorder;
                   if(get_class($nextObj) == "QuizQuestion") {
                       $link=get_permalink(self::$quiz->getPageId()) . $pdorder . '/' . self::REQUEST_QUESTION;   
                       $nextAction=array("task" => self::TASK_SHOWQUESTION , "url" => $link, "display_order" => self::$currentDisplayOrder +1);
                   }                       
                   else {
                       $link=get_permalink(self::$quiz->getPageId()) . $pdorder. '/' . self::REQUEST_SECTION;   
                       $nextAction=array("task" => self::TASK_SHOWSECTION , "url" => $link, "display_order" => self::$currentDisplayOrder +1);
                   }
               }
               else {
                    // no next obj in sequence, must go to optin page
                    $link=get_permalink(self::$quiz->getPageId()) . self::REQUEST_OPTIN;
                    $nextAction=array("task" => self::TASK_SHOWOPTIN , "url" => $link);   
               }
          } 
          
          
          
          if(self::$task == self::TASK_SHOWOPTIN) {
             $link=get_permalink(self::$quiz->getPageId()) . self::REQUEST_THANKYOU; 
             $nextAction=array("task" => self::TASK_SHOWTHANKYOU, "url" => $link);   
          }
          
          return $nextAction;
      }
      public static function saveToSession($key, $value=false) {
          if($key == false) {
            self::$sessionArray=array();
          }
          else {
            self::$sessionArray[$key]=$value;
          }
          $_SESSION["quizseq"]=serialize(self::$sessionArray); 
      }
      public static function addResultData($questionId, $answerIds) {
          global $wpdb;
          $resId=false;
          if(isset($_SESSION["quizresultid"  . self::$quiz->getId()]))
            $resId=$_SESSION["quizresultid"  . self::$quiz->getId()];
          $resId=intval($resId);
          if($resId < 1) {
            // add result info
            $d=array(
                "quiz_id" => self::$quiz->getId(),
                "status" => "i",
                "firstname" => "",
                "lastname" => "",
                "email" => "",
                "resultkey" => ""
            );
            $wpdb->insert(QUIZ_DB_TABLE_RESULTS , $d , array("%d", "%s", "%s" , "%s", "%s" , "%s"));
            $resId=$wpdb->insert_id;
            $_SESSION["quizresultid"  . self::$quiz->getId()]=$resId;
          }  
          
          // clear old answers
          $wpdb->query("DELETE FROM " . QUIZ_DB_TABLE_RESULT_DATA . " WHERE result_id = $resId AND question_id = $questionId ");
          
          // add result data
          foreach($answerIds as $answerId) {
              $d=array(
                "result_id" => $resId,
                "question_id" => $questionId,
                "answer_id" => $answerId
              );
              $wpdb->insert(QUIZ_DB_TABLE_RESULT_DATA , $d, "%d");
          }
      }
      public static function hasResultData($questionIds) {
          global $wpdb;
          if(!(is_array($questionIds) && count($questionIds)))
            return true;
          $resId=false;
          if(isset($_SESSION["quizresultid" . self::$quiz->getId()]))
            $resId=$_SESSION["quizresultid" . self::$quiz->getId()];
          $resId=intval($resId);
          if($resId < 1)
            return false;
          $d=$wpdb->get_results("SELECT question_id FROM " . QUIZ_DB_TABLE_RESULT_DATA . " WHERE result_id = " . $resId);
          if($d) {
            $rqids=array();  
            foreach($d as $qid) {
               $rqids[]=$qid->question_id;    
            }
            foreach($questionIds as $questionId) {
                if(!in_array($questionId , $rqids)) 
                    return false;   
            }
            return true;
          } 
          return false;
      }
      public static function getResultKey($resId=false) {
        global $wpdb;
        if($resId === FALSE) {
           if(isset($_SESSION["quizresultid" . self::$quiz->getId()]))
                $resId=$_SESSION["quizresultid" . self::$quiz->getId()]; 
        }
        $has=false;
        $resultKey=substr(md5("resultkey" . $resId) , 0 , 10);
        do {
            // check if has result key
            $c=$wpdb->get_var("SELECT COUNT(id) as c FROM " . QUIZ_DB_TABLE_RESULTS . " WHERE resultkey = '$resultKey' ");
            if($c) {
                $has=true;
                $resultKey=substr(md5("resultkey" . $resId . rand(0, 100)), 0 , 10);
            }
            else
                $has=false;    
        }while($has);
        return $resultKey; 
      }
      public static function processString($string, $replacements) {
        return str_replace(array_keys($replacements) , array_values($replacements) , $string);   
      }
      
  }
