<?php

$check = mail("leeqj-wp22@student.tarc.edu.my", "Testing Email", "This is a testing email from XAMPP", "From:leedannyqj@gmail.com");

if ($check) {
    echo "Sucess";
} else {
    echo "Fail";
}
