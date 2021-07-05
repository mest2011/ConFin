<?php
session_start();
session_destroy();
header("LOCATION: ../../cofrin/view/login.html?logout=true");




?>