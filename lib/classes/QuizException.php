<?php
  class QuizException extends Exception {
      const NO_DATA=1;
        
      public function QuizException($message , $code) {
           parent::__construct($message , $code);
      }   
  }

