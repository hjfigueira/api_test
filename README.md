# Example Application

## How it works
This example application uses PGSql as database, 3 instances of the same codebase and 1 instance of kafka.

For the check duplicates, the data flow is as follows :

 - **API** ------- ( on updating fund, calls worker )
 - **Worker** -- ( recieve the request for dup check, if found, calls kafka )
 - **Kafka** ---- ( stores and distributes the duplicated records message)
 - **Listener**-- ( stores duplicates in the database )

Alternatively, by changing the docker-compose file, the application can run a background check every minute.

Note: 
1. Duplicates are reinserted in the database, if they're already not present, or if the record found is marked as resolved = true. 
2. The duplicates parent_id/duplicate_id is reversible, so inserting the relation in both directions is avoided. 

## Run Instructions

### Requirements.
1. Having docker compose or docker-compose installed locally.
2. Having git installed locally.

### How to run.
1. Clone the project;
2. Copy the file `./src/.env.dev.example` to `./src/.env`. Values for dev should be already configured.
3. Start the containers with `docker compose up -d`.
   1. Might be  `docker-compose up -d` depending on your docker version.
4. Install dependencies `docker compose exec php-fpm composer install`
5. Build the database `docker compose exec php-fpm php artisan migrate:fresh --seed`

## Improvements
- Actions can be further abstracted to and individual action basis (possible use of php traits) 
- Add a log/history table of the duplicated records.
- Splitting databases & app depending on the domain, and further increasing app communication. e.g. the duplicated fund candidate table shouldn't be in the same database as the main API, should belong to a separated app that handles only that, and if needed, communicate back to the main app thru events.
- Query optimization, some eloquent queries although simpler to develop, may generate overly complicated final query statements

## Considerations
- Currently, the communication back to the worker, is done thru the database, other methods could be investigated.
- After building the project from scratch, the first message might be lost on kafka due the topic not being created.

## Extras
- phpstan `docker-compose exec php-fpm ./vendor/bin/phpstan`
- php code sniffer `docker-compose exec php-fpm ./vendor/bin/phpcs`
- php tests `docker compose exec php-fpm php artisan test`
- github actions currently run tests, sniffer and phpstan

## Tasks
- ~~Database Diagram~~
- ~~Docker dev setup~~
- ~~Api implementation~~
- ~~Implement dependency injection~~
- ~~dependent services and event processing~~
- ~~Implement full scan dup check.~~
- ~~Implement filtering~~
- ~~Add style checks~~
- ~~Test deployment from zero.~~
- ~~Create API tests~~
- Unit tests to the classes that search for Dups.
