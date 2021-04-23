# applause

This is a very simple tool for spending applause in online performances. It is embedded in a ordinary website. Everyone who has the Website open, hears the handclapping of everyone currently giving applause.

It is based on Javascript (only Framework: [howlerJS](https://howlerjs.com])) and PHP. It stores its state in textfiles and does not require any database.

## Files
|What | File | Content
|--- |--- |---
Interface | applause_client.html | sample Interface for giving applause. Using JS-Functions startApplauding() and stopApplauding()
Interface | applause_server.html | sample Interface for an admin site, which can stop all applause, in case you want to restart the performance or if anything breaks.
Client | applause_sse.js | main component clientside. Sends your applause to the server and listen for applause from other users
Client | applause_audio.js_| plays the sound of hands clapping, using howlerJS. *attention*: Many Browsers only play Javascript-Sound if there has been any interaction on them. If you only load a page, you will not hear remote clapping, unless you click anything on the page (e.g. when applauding on your own)
Server | applause_handler.php | main compoment serverside, increasing or decreasing current applause and returns current (remote) applause.
Server | applause_sse_applauding_users.php | Stream for Javascript-ServerSideEvents, counts the currentrly applauding users.
Server | applause_sse_registered_users.PHP | Stream for Javascript-ServerSideEvents, counts the users, who have the website currently open.
Server | applause_numbers_in_file.PHP | Helper for storing current state in files on server.
Client | howler.js | Audio-Framework
