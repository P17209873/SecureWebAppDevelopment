The CreateDatabaseScript.sql file first drops (deletes) the database SWADCoursework if it already exists, and then (re)creates it and tells the MySQL server software to use the newly created database.

The script then creates a new user called 'coursework' and grants all of the required permissions to the newly created user.

The next step for the script is to drop any table that may already exist, and then create the following 3 tables: Users, RetrievedMessages, and Logs.

These tables all contain the required information and schematics for the application to run.