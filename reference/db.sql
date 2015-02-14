
create table Users (
    ID bigint unsigned NOT NULL AUTO_INCREMENT,
    remoteId bigint unsigned NOT NULL,
    ip varchar(100) NOT NULL,
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

create index TimeIndex on Log(time);
create index IsHomeIndex on Log(isHome);
create index userIndex on Log(user);