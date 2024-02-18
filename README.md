# Example Application




## Improvements
- Actions can be further abstracted to and individual action basis (possible use of php traits) 
- Migrate to different applications and databases.
- Add a log/history table of the duplicated records.
- Splitting databases depending on the domain, and further increasing app communication. e.g. the duplicated fund candidate table shouldn't be in the same database as the main API, should belong to a separated app that handles only that, and if needed, communicate back to the main app thru events.
- Query optimization, some eloquent queries although simpler to develop, may generate overly complicated final query statements

## Considerations
- The current app also fires the check duplicate routine on fund update and creation, in an on demand basis.

