<?php

$promotions = [

    [
        "name" => "Bulk Purchase",
        "code" => "",
        "type" => "percentage",
        "value" => 10,
        "minimum_subtotal" => 1000,
        "automatic" => true
    ],

    [
        "name" => "CINE15",
        "code" => "CINE15",
        "type" => "percentage",
        "value" => 15,
        "minimum_subtotal" => 0,
        "automatic" => false
    ],

    [
        "name" => "CINE20",
        "code" => "CINE20",
        "type" => "percentage",
        "value" => 20,
        "minimum_subtotal" => 1500,
        "automatic" => false
    ],

    [
        "name" => "STUDENT10",
        "code" => "STUDENT10",
        "type" => "percentage",
        "value" => 10,
        "minimum_subtotal" => 500,
        "automatic" => false
    ],

    [
        "name" => "WELCOME50",
        "code" => "WELCOME50",
        "type" => "fixed",
        "value" => 50,
        "minimum_subtotal" => 300,
        "automatic" => false
    ]

];

?>