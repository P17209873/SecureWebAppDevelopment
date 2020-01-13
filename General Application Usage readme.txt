This file contains a full guide to using the web application.

The applications' first screen is a login screen, with a login button and a register button. Once the user is registered/logged in, the webpage will show a homepage, containing:

The current state of the circuitboard
A radio button selection - view messages and send messages

If the user selects the view messages radiobutton, they are displayed with a table of the current message data that is stored in the database. There is a link on this page that allows the user to update the database, the original plan was to use a cronjob to update the database every 5 minutes manually but this was not implemented due to issues with the production server. 

The sendmessages route allows the user to update the circuitboard state back onto the EE M2M server. This uses front end validated fields (radio buttons and text fields) to ensure that the user cannot enter invalid values. 