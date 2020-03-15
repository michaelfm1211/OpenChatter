<?php

// This file declares many constants which are used for configuration. Change them to whatever you want, but we suggest you read the comments before you break something

define("GCP_OAUTH2_CLIENT_ID_SECRET", "client_secret.json");	// The path relative to this directory where your google cloud oauth client id file is

define("ENABLE_API", false);	// Switching to true lets other sites to chat messages, usefull for chatbots, but could be used to steal bandwidth

define("CHATFILE", "chatfile.txt");	// The path relative to this directory where the chatroom data is

define("POLLING_RATE", 1000);	// How often the client should poll the server for new messages in milliseconds. Reasonable times are 1000(once a second), or for a snappier site 500(twice a second), or if you're the millitary or something 100(ten times a second). Remember, more times a second results in heavier workload for the server, which may slow everything down