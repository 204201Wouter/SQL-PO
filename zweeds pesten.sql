CREATE TABLE `Users` (
  `id` integer,
  `username` integer,
  `password` timestamp
);

CREATE TABLE `Servers` (
  `id` text,
  `player1` integer,
  `player2` integer,
  `player3` integer,
  `player4` integer,
  `turn` integer,
  `stapel` text,
  `pakstapel` text
);

CREATE TABLE `Players` (
  `id` integer,
  `hand` text,
  `kaartenvooropen` text,
  `kaarenvoorgesloten` text,
  `gameid` text
);

ALTER TABLE `Players` ADD FOREIGN KEY (`id`) REFERENCES `Servers` (`player1`);

ALTER TABLE `Players` ADD FOREIGN KEY (`id`) REFERENCES `Servers` (`player2`);

ALTER TABLE `Players` ADD FOREIGN KEY (`id`) REFERENCES `Servers` (`player3`);

ALTER TABLE `Players` ADD FOREIGN KEY (`id`) REFERENCES `Servers` (`player4`);

ALTER TABLE `Players` ADD FOREIGN KEY (`id`) REFERENCES `Servers` (`turn`);

ALTER TABLE `Servers` ADD FOREIGN KEY (`id`) REFERENCES `Players` (`gameid`);

ALTER TABLE `Users` ADD FOREIGN KEY (`id`) REFERENCES `Players` (`id`);
