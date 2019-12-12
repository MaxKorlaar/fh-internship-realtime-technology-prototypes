start_pusher:
	docker-compose -f pusher/docker-compose.yml up -d

stop_pusher:
	docker-compose -f pusher/docker-compose.yml down

start_sse:
	docker-compose -f server-sent-events/docker-compose.yml up -d

stop_sse:
	docker-compose -f server-sent-events/docker-compose.yml down

