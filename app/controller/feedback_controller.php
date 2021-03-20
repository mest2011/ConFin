<?php
include_once "../business/feedback_bus.php";

class FeedbackController{

    function salvar($obj_feedback){
        return FeedbackBus::createFeedback($obj_feedback);
    }

}



?>