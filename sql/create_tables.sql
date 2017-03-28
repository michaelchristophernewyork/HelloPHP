drop table if exists People cascade;
create table People ( id serial primary key, data json );
insert into People (data) values ( '{ "name": "michael", "Microsoft": "michaelchristophernewyork@hotmail.com", "email": "michaelchristophernewyork@hotmail.com", "Github": "mcny" } '::json);
insert into People (data) values ( '{ "name": "Bill Gates", "Microsoft": "billg@microsoft.com", "email": "billg@microsoft.com" } '::json);
select id, data from people;