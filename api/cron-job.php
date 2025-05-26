<?php

$time_created = time();
$conn = mysqli_connect('localhost', 'root', '', 'demo');

$query = mysqli_query($conn, "INSERT INTO demo(time_created) VALUES('$time_created')");