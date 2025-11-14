CREATE TABLE `Users` (
  `id` integer PRIMARY KEY,
  `username` VARCHAR(36),
  `password` VARCHAR(36)
);

CREATE TABLE `Servers` (
  `id` VARCHAR(36) PRIMARY KEY,
  `player1` integer,
  `player2` integer,
  `player3` integer,
  `player4` integer,
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
  `hand` text,
  `kaartenvooropen` text,
  `kaarenvoorgesloten` text,
  `gameid` VARCHAR(36)
);

ALTER TABLE `Servers` ADD FOREIGN KEY (`player1`) REFERENCES `Users` (`id`);

ALTER TABLE `Servers` ADD FOREIGN KEY (`player2`) REFERENCES `Users` (`id`);

ALTER TABLE `Servers` ADD FOREIGN KEY (`player3`) REFERENCES `Users` (`id`);

ALTER TABLE `Servers` ADD FOREIGN KEY (`player4`) REFERENCES `Users` (`id`);

ALTER TABLE `Games` ADD FOREIGN KEY (`id`) REFERENCES `Servers` (`id`);

ALTER TABLE `Players` ADD FOREIGN KEY (`gameid`) REFERENCES `Games` (`id`);

ALTER TABLE `Games` ADD FOREIGN KEY (`turn`) REFERENCES `Players` (`id`);
