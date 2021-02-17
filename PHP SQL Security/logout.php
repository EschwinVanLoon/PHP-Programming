<?php

require_once 'php/functions.php';

gebruikerUitloggen();
session_destroy();

header('Location: home.php');
die();