#User Ping Tracker
This is a web service api to enable tracking of users by Ip address. 

##Usage and Setup
###Requirement
 - Apache web server
 - MySQL
 - PHP5

###Setup
 1. Drop this project into your virtual host folder (Defualt `/var/www` on linux systems)
 2. Create a database with the db.sql file found under `reference/db.sql`
 3. Copy `SQLConnect.php.example` to `SQLConnect.php`, then fill out the required fields. 
 4. Add `cronPing.php` to cron at the interval of your choice

###Additional info
This has no access control, encryption or account management built in. DO NOT expose this directly to the internet, this is designed to run on a private network. The API this prject provides is designed to be consumed by the RBF-Autiomation/Web-Service project. This is an optional component. 

##API
API calls are accessed by calls to the web server this is running on followed by `/api/` and the particular API call. 
Below is the API documentation. 

###Documentation


##/api/getUsers.php
Gets all the users
#####GET
 - none
 
#####POST
 - none

#####Response
    
    [
        {
            "ip":     "IP ADDRESS",
            "isHome":  true/false,
            "status": "STATUS MESSAGE"
        }
    ]
    
-------
##/api/newUser.php
Creates a new user, or re-activates a de-activated user. 
#####GET
`ip` = `ipAddress`

Example

    /api/newUser.php?ip=192.168.1.1
#####POST
- none
    
#####Response
    
    {
        "result":   true/false,
        "message":  "Success or Fail"
    }
    
-------
##/api/removeUser.php
de-activates a user (does not actually delete)
#####GET
`ip` = `ipAddress`

Example

    /api/removeUser.php?ip=192.168.1.1

#####POST
- none

#####Response

    {
        "result":   true/false,
        "message":  "Success or Fail"
    }

-------
##/api/isHome.php
Returns true/false weather a user is "home"
#####GET
`ip` = `ipAddress`

Example

    /api/isHome.php?ip=192.168.1.1

#####POST
- none

#####Response

    {
        "result":   true/false,
        "message":  "Success or Fail"
    }