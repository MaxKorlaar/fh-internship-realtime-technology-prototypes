# Real-time-technologieën prototypes

Stageproject van Max Korlaar

De servers werken middels Docker-containers, zodat het niet nodig is om lokaal een webserver te installeren.

## Eisen
[Docker](https://www.docker.com/products/docker-desktop) moet geïnstalleerd zijn op je computer.

Voor het installeren van Composer-pakketten, is [Composer](https://getcomposer.org/download/) vereist. Hiervoor is het nodig om PHP te hebben geïnstalleerd op je computer. Op Windows is dit het makkelijkst met een WAMP-server-installatie, of het gebruik van een virtuele Ubuntu machine binnen Windows. Instructies hiervoor zijn specifiek en afhankelijk van je eigen situatie.

## Server-Sent Events

[Broncode](server-sent-events)

Voordat het prototype werkt, moet er nog een Composer-pakket worden geïnstalleerd. Dit kan met het volgende commando in je terminal, nadat je naar de juiste map bent genavigeerd:

```shell
cd server-sent-events
composer install
```

Start dit prototype vervolgens door het volgende commando uit te voeren, waarmee je Docker instrueërt om de virtuele machines te starten:

```bash
docker-compose up -d
```

Als alles goed is gestart, dan kan je het prototype nu in je browser benaderen door naar <http://localhost:8100> te gaan.
Het prototype ziet er als volgt uit, echter zonder inhoud in het grijze vlak:

![](server-sent-events/screenshot.png)

Door nieuwe notificaties aan te maken middels het formulier, komen ze in de database met notificaties terecht. Ze zullen automatisch middels Server-Sent Events naar de browser gestuurd worden, en door JavaScript worden uitgelezen waardoor ze op de pagina belanden.

#### Gebruik
Server-Side Events werkt door een HTTP-aanvraag naar de server te versturen vanuit JavaScript. Deze aanvraag wordt echter niet door de server gesloten en blijft zodanig openstaan tot zover dit nodig is. Terwijl de verbinding open staat, is het voor de server mogelijk om berichten te verzenden naar de browser, gescheiden door 2 witregels. Hierdoor is de implementatie op een PHP-server niet moeilijk en heb ik hier het pakket ['PHP SSE'](https://github.com/hhxsv5/php-sse) gebruikt, gevonden op GitHub.
