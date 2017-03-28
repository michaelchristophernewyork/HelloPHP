drop table if exists People cascade;
create table People ( id serial primary key, data json );
insert into People (data) values ( '{ "name": "michael", "Microsoft": "michaelchristophernewyork@hotmail.com", "email": "michaelchristophernewyork@hotmail.com", "Github": "mcny" } '::json);
select id, data from people;