<?php


add_action('init', function()
{
	include dirname(__FILE__).'includes/class-stock-data-scrape.php';
	new Stock_Data_Scrape();
});