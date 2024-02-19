# Example Application

## Run Instructions

### Requirements.
1. Having docker compose or docker-compose installed locally.
2. Having git installed locally.

### How to run.
1. Clone the project;
2. Copy the file `.env.dev.example` to `.env`. Values for dev should be already configured.
3. Start the containers with `docker compose up`. Might be  `docker-compose up -d` depending on your docker version.
   1. All imagine start after the composer service finishes.
 

## Improvements
- Actions can be further abstracted to and individual action basis (possible use of php traits) 
- Migrate to different applications and databases.
- Add a log/history table of the duplicated records.
- Splitting databases depending on the domain, and further increasing app communication. e.g. the duplicated fund candidate table shouldn't be in the same database as the main API, should belong to a separated app that handles only that, and if needed, communicate back to the main app thru events.
- Query optimization, some eloquent queries although simpler to develop, may generate overly complicated final query statements

## Considerations
- The current app also fires the check duplicate routine on fund update and creation, in an on demand basis.
- Currently, the communication back to the worker, is done thru the database, other methods could be investigated.
- Docker compose and docker file should migrate the "composer" step, to the build.

## Tasks
- ~~Implement full scan dup check.~~
- ~~Implement filtering~~
- ~~Implement dependency injection~~
- Create API tests
- Maybe some Unit tests to the classes that search for dups.
- ~~Add style checks~~
- Test deployment from zero.
