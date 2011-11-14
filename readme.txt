Architecture Notes:
Admin is at /lib/admin folder
Admin html files (views) are in /lib/admin/views/

Front end is in /front/ folder
Front end is mainly controlled by a static class QuizFront (/front/QuizFront.php).
All the html in front end is in /front/views/
Front end uses /css/quiz.css

If you want to modify the front end design, then you will need to modify quiz.css (you can modify html files too if you want, but be carefull). If you want to modify front end functionality then modify QuizFront class. 

I dont know which kind of list you want, but I guess you can directly observe quiz.css and html files.
Some of the main css classes in front end are:

.quiz-result-unit-section { //section heading in result page
    font-weight: bold;
    margin-bottom: 10px;
}
.quiz-result-unit-response { // response in result page
    margin: 10px;
}
.quiz-container-optin .question-form-container {       // optin form
    margin: 0px auto;
    width: 190px;
}
.quiz-share-links { // social links
    line-height: 1;
    margin-top: 10px;
    margin-bottom: 10px;
    
}
.quiz-share-link-twitter, .quiz-share-link-like, .quiz-share-link-share { // social links
    float: left;
}
.quiz-share-link-share { // share button
    padding-right: 18px;
    text-align: left;
}
.quiz-container .intro-content, .quiz-container .section-content ,.quiz-container .question-content, .quiz-container .optin-content, .quiz-container .thankyou-content{ // main content div, different in different pages
    text-align: center;
}

one div wraps the content, class depends on page =>
.quiz-container-intro 
.quiz-container-question
.quiz-container-section
.quiz-container-optin
.quiz-container-thankyou
.quiz-container-result

check quiz.css for rest of the css. Also I will recommend Firebug plugin for Mozilla Firefox (to check/test css and a lot of other designers task)

v2 c2