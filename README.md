# SaveOneSong WordPress Plugin 

Imagine you're in the car and listening to a great song on your favorite station egoFM. No pen to hand to record the song you just say 'Hey Siri, SOS egoFM.' and you'll receive the current song as a message directly to your mobile phone including the song title and its artist.

The SaveOneSong WordPress Plugin allows you to extract the currently played song on 'egoFM' and automatically pushes it to different notification-channels if enabled. It also supports all other streams like

- "egofm"
- "egosun"
- "egoflash"
- "egosoul"
- "egoriff"
- "egorap"
- "egosnow"
- "egopure"
- "50fresh-rap"
- "50fresh-elektro"
- "50fresh"
- "egofmseewald"

## How to Install

Same procedure as with any other Wordpress Plugin. Upload, extract, have fun.

## Configuration

To push a notification you have to configure your prefered channel(s). This plugin offers an Option page called "SoS Settings" to the left in the Admin area.

### Setup Telegram 

To use Telegram you have to generate a token by the so called 'botfather'. 

1. Open Telegram and add User @botfather to your users (use 'search')

2. Now send a message to @botfather 

```bash
/newbot
```

The BotFather then asks a few questions, i.a. according to the desired name of the bot to be created (Example: chatbot). Afterwards you'll receive your token which is used to access the HTTP-API.

3. Now we send from the Telegram app any message to the just created bot (chatbot).

4. Open the following site in your favorite browser and replace <token> with your fresh one.

```bash
https://api.telegram.org/bot<token>/getUpdates
```

5. After submitting, the above page shows something like:

```bash
{"ok":true,"result":[{"update_id":638422092,
"message":{"message_id":9,"from":{"id":268963852,"first_name":"Max"},"chat":{"id":438963812,"first_name":"Max"}," date":1437389925,"text":"c"}},{"update_id":638422093,
````

From this we only need the value of chat 'id', here: 438963812

6. Now we can send a message via HTTP to our bot (chatbot) by calling the following URL in the browser using the following pattern:

```bash
https://api.telegram.org/bot<token>/sendMessage?chat_id=<id>&text="Welcome to SoS!"
```

7. Done.

## Usage

To grab the current song of your prefered stream use, eg. 'egofm'

```bash
https://<wordpress_hosting>/wp-json/sos/v1/find-song/stream/egofm
```

You'll receive a JSON-response like this

```bash
{"track":"Flut - Cocktailbar","stream":"egofm","timestamp":1565117312,"telegram":"send"}
```

If you have enabled Telegram notifications, a field named 'telegram' is added to the response. So you shoukd instantly receive a message in your app.

### Configuration iOS app 'Kurzbefehle'

Install app on your phone and create a 'Kurzbefehl'. Now add the following actions to it:

1. Add action 'URL': use the url from 'Usage' with your prefered egoFM stream as last part of the url.

2. Add action 'Inhalte von URL abrufen'. Choose 'get' as methode

3. Add a Sire phrase of your choice.

4. Done.


## Some Notes

Wordpress Option page for this Plugin is based on a simple Vue.js implementation.

## ToDo
- adding notification-channels like Email, Mattermost, Blog post
- adding track to prefered Spotify playlist automatically if found
- adding basic security to REST-endpoint
- replacing JQuery-Ajax with [Axios](https://github.com/axios/axios)
