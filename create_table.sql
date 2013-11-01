create table bag (
bagid int(20) auto_increment,
name varchar(20) unique not null,
primary key(bagid)
);

create table client (
cid int(10) auto_increment,
fname varchar(20),
lname varchar(20), 
phone int(10),
gender char(1),
dob date,
start date, 
pday int(2), 
bagid int(10) not null,
street varchar(20),
city varchar(20), 
state char(3), 
zip int(5), 
apt int(5), 
primary key (cid),
foreign key (bagid) references bag(bagid),
-- foreign key (pid) references pickup(pid),
unique(fname, lname, phone)
);

create table family (
cid int(10) not null,
fname varchar(20), 
lname varchar(20), 
dob date, 
gender char(1),
-- ADDED cid AS A PRIMARY KEY BECAUSE family IS A WEAK ENTITY TYPE
primary key (fname, lname, cid),
foreign key (cid) references client(cid)
);

create table user (
username varchar(20),
fname varchar(20), 
lname varchar(20), 
password varchar(20),
email varchar(40),
type tinyint(1),
primary key (username)
);

create table pickup (
pid int(10) auto_increment,
pdate date,
cid int(20) not null,
bagid int(20) not null,
primary key (pid),
foreign key (cid) references client(cid),
foreign key (bagid) references bag(bagid)
);


create table source(
sourceid int(10) auto_increment,
name varchar(20) unique not null, 
primary key(sourceid)
);

create table product (
prodid int(10) auto_increment,
name varchar(20) unique not null,
cost int(6),
sourceid int(10) not null,
primary key (prodid),
foreign key (sourceid) references source(sourceid)
);

create table dropoff(
did int(10) auto_increment,
ddate date,
qty int(6),
sourceid int(10) not null,
prodid int(10) not null,
foreign key (sourceid) references source (sourceid),
foreign key (prodid) references product (prodid),
primary key(did)
);

create table contents(
bagid int(10),
prodid int(10),
qty int(6),
prevqty int(6),
primary key(bagid, prodid),
foreign key (bagid) references bag(bagid),
foreign key (prodid) references product(prodid)
);

create table aidsrc(
aid int(10) auto_increment,
name varchar(20) unique not null,
fedstate tinyint(1),
primary key (aid)
);

create table finaid(
cid int(10), 
aid int(10),
primary key(cid, aid),
foreign key (cid) references client(cid),
foreign key (aid) references aidsrc(aid)
);


CREATE VIEW bagclients AS
SELECT bagid,
            COUNT(*) AS numClients
     FROM client
     GROUP BY bagid;


CREATE VIEW baginfo AS
  SELECT b.bagid,
         b.name,
         SUM(c.qty * p.cost) AS cost,
         SUM(qty) AS numItems,
         numClients
    FROM contents c
    JOIN bag b ON c.bagid = b.bagid
    INNER JOIN product p ON c.prodid = p.prodid
    LEFT JOIN bagclients cl ON cl.bagid = b.bagid
    GROUP BY b.bagid;

CREATE VIEW oldbaginfo AS
  SELECT b.bagid,
         b.name,
         SUM(c.prevqty * p.cost) AS cost,
         SUM(prevqty) AS numItems,
         numClients
    FROM contents c
    JOIN bag b ON c.bagid = b.bagid
    INNER JOIN product p ON c.prodid = p.prodid
    LEFT JOIN bagclients cl ON cl.bagid = b.bagid
    GROUP BY b.bagid;


--TESTING RELATED CODE

INSERT INTO bag VALUES(null, "Test Bag");
INSERT INTO bag VALUES(null, "Test Bag2");
INSERT INTO source VALUES(null, "Publixs");
INSERT INTO product VALUES(null, "Peaches", 2, 1);
INSERT INTO product VALUES(null, "Grapes", 8, 1);
INSERT INTO product VALUES(null, "Apple", 3, 1);
INSERT INTO contents VALUES(1, 1, 3, 0);
INSERT INTO contents VALUES(1, 2, 10, 0);
INSERT INTO contents VALUES(2, 1, 7, 0);
INSERT INTO contents VALUES(2, 2, 4, 0);


INSERT INTO client VALUES(
    null, "Evan", "Cahill", null, null, MAKEDATE(1993,5), null, 5, 1, null, null, null, null, null
);

INSERT INTO client VALUES(
    null, "Kim", "Cahill", null, null, MAKEDATE(1999,5), null, 15, 2, null, null, null, null, null
);

INSERT INTO client VALUES(
    null, "Robert", "Cahill", null, null,  MAKEDATE(1980,5), null, 6, 1, null, null, null, null, null
);

INSERT INTO family VALUES(1, "Addison", "Cahill" ,NOW(), null);
INSERT INTO family VALUES(1, "Someone", "Cahill" ,NOW(), null);
INSERT INTO family VALUES(1, "Danielle", "Cahill" ,MAKEDATE(1995,5), null);


INSERT INTO pickup VALUES(null, NOW(), 1, 1);
INSERT INTO dropoff VALUES(null, NOW(), 10, 1, 1);
