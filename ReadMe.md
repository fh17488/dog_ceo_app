# Installation

Started with Docker compose here: https://github.com/bitnami/bitnami-docker-laravel and modified the Dockerfile to use PHP 8 as per guidance here: https://github.com/bitnami/bitnami-docker-laravel/issues/147

The users table and the parks table were populated manually via SQL CLI and not using a factory. 

## Notes for part 1:
The /breed API must be called before any other API. This is because a call to /breed populates the database that the other calls rely on.

## Notes for part 2:
Every time the /breed API is called it upserts data into the database as well as REDIS. Data inserted into REDIS can be tested by invoking the additional API endpoint /test. Also, the breedable table exists but is not used by any endpoint. Also, all data relationships have been setup as many-to-many relationships. This is because a user may have many breeds but a breed may belong to more than one user and a park can allow more than one breed while multiple parks can allow the same breed.

Finally, delete for breeds has not been setup.

## Notes for part 3:
The only query that has not been setup is the one for breeds.
