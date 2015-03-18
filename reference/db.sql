
create table Users (
    ID bigint unsigned NOT NULL AUTO_INCREMENT,
    ip varchar(100) NOT NULL UNIQUE,
    active int NOT NULL,
    PRIMARY KEY (ID)
);

create table Log (
    ID bigint unsigned NOT NULL AUTO_INCREMENT,
    user bigint unsigned NOT NULL,
    isHome int NOT NULL,
    time int unsigned NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (user) REFERENCES Users(ID)
);

create index IpIndex on Users(ip);
create index TimeIndex on Log(time);
create index IsHomeIndex on Log(isHome);
create index userIndex on Log(user);