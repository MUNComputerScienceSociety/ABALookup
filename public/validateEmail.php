<?php

function checkEmail($email) {
    var_dump(filter_var($email, FILTER_VALIDATE_EMAIL));
}
