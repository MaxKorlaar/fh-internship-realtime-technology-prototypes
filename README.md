# Real-time-technologieën prototypes

Stageproject van Max Korlaar

De servers werken middels Docker-containers, zodat het niet nodig is om lokaal een webserver te installeren.

## Eisen
Docker moet geïnstalleerd zijn op je computer.

## Server-Sent Events

[Broncode](server-sent-events)

Start dit prototype door de volgende commando's uit te voeren in je terminal, nadat je in de juiste map bent genavigeerd:

```bash
cd server-sent-events
docker-compose up -d
```

Als alles goed is gestart, dan kan je het prototype nu in je browser benaderen door naar <http://localhost:8100> te gaan.
Het prototype ziet er als volgt uit, echter zonder inhoud in het grijze vlak:

![](server-sent-events/screenshot.png)

Door nieuwe notificaties aan te maken middels het formulier, komen ze in de database met notificaties terecht. Ze zullen automatisch middels Server-Sent Events naar de browser gestuurd worden, en door JavaScript worden uitgelezen waardoor ze op de pagina belanden.

#### Gebruik
Server-Side Events werkt door een HTTP-aanvraag naar de server te versturen vanuit JavaScript. Deze aanvraag wordt echter niet door de server gesloten en blijft zodanig openstaan tot zover dit nodig is. Terwijl de verbinding open staat, is het voor de server mogelijk om berichten te verzenden naar de browser, gescheiden door 2 witregels. Hierdoor is de implementatie op een PHP-server niet moeilijk en heb ik hier het pakket ['PHP SSE'](https://github.com/hhxsv5/php-sse) gebruikt, gevonden op GitHub.
