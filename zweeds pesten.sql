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
  `pakstapel` text
);

CREATE TABLE `Players` (
  `id` integer PRIMARY KEY,
  `user` integer,
  `hand` text,
  `kaartenvooropen` text,
  `kaartenvoorgesloten` text,
  `serverid` VARCHAR(36),
  `nummer` integer
);

ALTER TABLE `Servers` ADD FOREIGN KEY (`gameid`) REFERENCES `Games` (`id`);

ALTER TABLE `Players` ADD FOREIGN KEY (`user`) REFERENCES `Users` (`id`);

ALTER TABLE `Players` ADD FOREIGN KEY (`serverid`) REFERENCES `Servers` (`id`);
