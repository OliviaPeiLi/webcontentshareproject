<?php

$route['help']                                        = 'walkthrough'; //Defines the default controller

//Help and Walkthrough
$route['walkthrough/(:any)']                          = "help/walkthrough/get_walkthrough/$1";
