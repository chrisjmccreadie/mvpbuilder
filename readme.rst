MVP Builder

What is this?

MVP builder is my framework I use to build all my MVP products.  It has been designed to get you out of the door fast taking care of all the boring bit like admin / sign up etc. 
It is very lightweight and comes with a bare minimum design allowing you to easy skin it as you require. It allows me to build pretty complex MVP's in 8 weeks or less.

Note this is aimed at developers who wants to build something fast, it should not be used for production large scale projects.  That is usually the second iteration for myself and more often than not I build these using Node.JS and NoSQL.

It has been built in PHP and the Codeigniter framework, with Jquery and bootstrap, why?  These are amongst the most popular technologies and are widely understood.  

It is not built as a micro service but it has been designed to easily extend into one admin / api and www are all separate code bases.  

I built this to serve my requirements and even though it is based on decades of doing this kind of thing it may not be for you.  Your coding standard may not meld naturally with mine and that is fine, simply look for something that better matches your style. 

What does it does?

Full admin uses natural SQL methodology to handle table management)
Rest API 
Memcachier support (caching)
S3 Support (hosting)
Sendgrid support (email)
User management (front end and admin)
Admin logging engine
flexible sql handling
All key features controlled from applicaiton/config/config.php
Meta data system for all tables allowing you to apply required,WYSIWGY editors, look up tables and so on


Natural SQL

The natural SQL system is pretty simple if you have a table called say

Products
   id
   productname
   cost

 productname
 	id
 	name

 in the admin when is rendering the products table will will look for another table that matches any of the fields and use this as the dropdown data to populate this field.  This can alos be over ridden with the SQL meta data where you can give it a look up table etc. 

Where to start

1) set all your parameters in config.pho
2) run sql.txt
3) create a vhost (mamp,wamp etc)
4) go to host/admin for admin
5) go to host for www

