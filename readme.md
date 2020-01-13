on a mac open mamp (on windows it would be wamp) to run the web server
in a web browser goto localhost:8888/phpMyAdmin
create your db use utf8 encoding; called it photos
create table; use autoincrement, 
    -image_id (unique image identifier within db; 1 to n)
    -image (path to image)
    -notes
    -upload time
    -uploadder (user name)

 Preview sql   

 on left colulmn click new to create user table besides images table
 then again to create a table for tags