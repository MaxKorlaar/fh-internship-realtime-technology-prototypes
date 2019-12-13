# Real-time-technologieën prototypes

Stageproject van Max Korlaar

De servers werken middels Docker-containers, zodat het niet nodig is om lokaal een webserver te installeren.

* [Server-Sent Events](#server-sent-events)
* [Pusher](#pusher)
* [Mercure](#mercure)

## Eisen
[Docker](https://www.docker.com/products/docker-desktop) moet geïnstalleerd zijn op je computer.

Voor het installeren van Composer-pakketten, is [Composer](https://getcomposer.org/download/) vereist. Hiervoor is het nodig om PHP te hebben geïnstalleerd op je computer. Op Windows is dit het makkelijkst met een WAMP-server-installatie, of het gebruik van een virtuele Ubuntu machine binnen Windows. Instructies hiervoor zijn specifiek en afhankelijk van je eigen situatie.

## TL;DR
In het kort: de 3 prototypes kunnen gestart of gestopt worden met `make`, als dat op je computer is geïnstalleerd:
```shell
make start_pusher
make stop_pusher
make start_see
make stop_sse
```
Per project is het nodig om `composer install` handmatig uit te voeren in de juiste map.

## Server-Sent Events

[Broncode](server-sent-events)

Voordat het prototype werkt, moet er nog een Composer-pakket worden geïnstalleerd. Dit kan met het volgende commando in je terminal, nadat je naar de juiste map bent genavigeerd:

```shell
cd server-sent-events
composer install
```

Start dit prototype vervolgens door het volgende commando uit te voeren, waarmee je Docker instrueërt om de virtuele machines te starten:

```shell
docker-compose up -d
```

De machines kunnen afgesloten worden met het volgende commando:

```shell
docker-compose down
```

Als alles goed is gestart, dan kan je het prototype nu in je browser benaderen door naar <http://localhost:8100> te gaan.
Het prototype ziet er als volgt uit, echter zonder inhoud in het grijze vlak:

![](server-sent-events/screenshot.png)

Door nieuwe notificaties aan te maken middels het formulier, komen ze in de database met notificaties terecht. Ze zullen automatisch middels Server-Sent Events naar de browser gestuurd worden, en door JavaScript worden uitgelezen waardoor ze op de pagina belanden.

#### Hoe het werkt
Server-Side Events werkt door een HTTP-aanvraag naar de server te versturen vanuit JavaScript. Deze aanvraag wordt echter niet door de server gesloten en blijft zodanig openstaan tot zover dit nodig is. Terwijl de verbinding open staat, is het voor de server mogelijk om berichten te verzenden naar de browser, gescheiden door 2 witregels. Hierdoor is de implementatie op een PHP-server niet moeilijk en heb ik hier het pakket ['PHP SSE'](https://github.com/hhxsv5/php-sse) gebruikt, gevonden op GitHub.


## Pusher

[Broncode](pusher)

Voordat het prototype werkt, moeten er nog Composer-pakketten worden geïnstalleerd. Dit kan met het volgende commando in je terminal, nadat je naar de juiste map bent genavigeerd:

```shell
cd pusher
composer install
```

Start dit prototype vervolgens door het volgende commando uit te voeren, waarmee je Docker instrueërt om de virtuele machines te starten:

```shell
docker-compose up -d
```

De machines kunnen afgesloten worden met het volgende commando:

```shell
docker-compose down
```

Als alles goed is gestart, dan kan je het prototype nu in je browser benaderen door naar <http://localhost:8100> te gaan.

Door nieuwe notificaties aan te maken middels het formulier, komen ze in de database met notificaties terecht. Ze zullen automatisch met WebSockets naar de browser gestuurd worden, en door JavaScript worden uitgelezen waardoor ze op de pagina belanden.

#### Hoe het werkt
Pusher is een externe dienst die het opzetten van een server met ondersteuning voor WebSockets uit handen neemt. WebSockets is een protocol om bilaterale communicatie tussen een browser (client) en een server op te zetten in realtime. Vanuit de PHP-server van dit project wordt naar Pusher een aantal gegevens verstuurd, die door Pusher automatisch naar de verbonden clients wordt doorgestuurd.

## Mercure

[Broncode](mercure)

Voordat het prototype werkt, moeten er nog Composer-pakketten worden geïnstalleerd. Dit kan met het volgende commando in je terminal, nadat je naar de juiste map bent genavigeerd:

```shell
cd mercure
composer install
```

Start dit prototype vervolgens door het volgende commando uit te voeren, waarmee je Docker instrueërt om de virtuele machines te starten:

```shell
docker-compose up -d
```

De machines kunnen afgesloten worden met het volgende commando:

```shell
docker-compose down
```

Als alles goed is gestart, dan kan je het prototype nu in je browser benaderen door naar <http://localhost:8100> te gaan. Een demopagina van Mercure is te zien op <http://localhost:8080>.

Door nieuwe notificaties aan te maken middels het formulier, worden ze vanuit de PHP-server doorgestuurd naar Mercure. Ze zullen dan automatisch middels Server-Sent Events naar de browser gestuurd worden vanuit Mercure, en door JavaScript worden uitgelezen waardoor ze op de pagina belanden.

#### Hoe het werkt
Mercure is een losstaande applicatie die het opzetten van een server die Server-Sent Events ondersteunt versimpelt. Het is vanuit PHP mogelijk om gegevens te versturen naar Mercure, die vervolgens automatisch deze gegevens naar de juiste verbonden browsers (clients) doorstuurt, op basis van het feit is ze gemachtigd zijn om deze informatie te ontvangen, en of ze überhaupt naar een bepaald onderwerp 'luisteren'. De authorisatie gebeurt middels JSON Web Tokens, versleutelde stukken JSON met informatie over de gemachtigde zenders en ontvangers. Deze tokens zijn versleuteld met een sleutelcode en worden ontsleuteld door Mercure, die over deze code bezit.
