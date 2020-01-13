The CreateDatabaseScript.sql file first drops (deletes) the database SWADCoursework if it already exists, and then (re)creates it and tells the MySQL server software to use the newly created database.

The script then creates a new user called 'coursework' and grants all of the required permissions to the newly created user.

The next step for the script is to drop any table that may already exist, and then create the following 4 tables: Users, CircuitBoardStates, RetrievedMessages, and UserLoginLogs.

These tables all contain the required information and database schematics for the application to run.

The Users table stores the user information that is created by the registration process, including (hashed and salted) passwords.

The CircuitBoardStates table stores the state of the circuit boards pulled from the EE M2M Connect service, which has been split off from the rest of the Message metadata.

The RetrievedMessages table stores the metadata retrieved from the EE M2M Connect server, alongside a reference (foreign key) to the relevant CircuitBoardStates record.

The UserLoginLogs table stores login logs of any user that tries to log in, their user id, whether their login succeeded or failed, and a timestamp of the login.

The dmu_sql_script.sql file is a modified version of this script which works on the mysql.tech.dmu.ac.uk production server.