CREATE TABLE `Users` (
  `id` integer PRIMARY KEY,
  `username` VARCHAR(36),
  `password` VARCHAR(36)
);

CREATE TABLE `Servers` (
  `id` VARCHAR(36) PRIMARY KEY,
  `gameid` VARCHAR(36),
  `started` tinyint
);

CREATE TABLE `Games` (
  `id` VARCHAR(36) PRIMARY KEY,
  `turn` integer,
  `stapel` text,
  `pakstapel` text,
  `winner` integer
);

CREATE TABLE `Players` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `user` integer,
  `hand` text,
  `kaartenvooropen` text,
  `kaartenvoorgesloten` text,
  `serverid` VARCHAR(36),
  `nummer` integer,
  `ready` tinyint
);

CREATE TABLE `Stats` (
  `id` integer PRIMARY KEY,
  `user` integer,
  `wins` integer,
  `gamesplayed` integer,
  `elo` integer
);

CREATE TABLE `Gameslog` (
  `id` integer PRIMARY KEY, 
  `date` TEXT
);

CREATE TABLE `Playerlog` (
  `id` integer PRIMARY KEY AUTO_INCREMENT, 
  `gameid` integer,
  `playerid` integer,
  `elo` integer,
  `elodiff` integer
);

ALTER TABLE `Servers` ADD FOREIGN KEY (`gameid`) REFERENCES `Games` (`id`);

ALTER TABLE `Players` ADD FOREIGN KEY (`user`) REFERENCES `Users` (`id`);

ALTER TABLE `Players` ADD FOREIGN KEY (`serverid`) REFERENCES `Servers` (`id`);

ALTER TABLE `Stats` ADD FOREIGN KEY (`user`) REFERENCES `Users` (`id`);

ALTER TABLE `Playerlog` ADD FOREIGN KEY (`gameid`) REFERENCES `Gameslog` (`id`);
ALTER TABLE `Playerlog` ADD FOREIGN KEY (`playerid`) REFERENCES `Users` (`id`);


INSERT INTO Users (id, username) VALUES (-1, 'bot');