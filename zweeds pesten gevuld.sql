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

INSERT INTO Users (id, username, password) VALUES
(1, 'Alice', 'pass1'),
(2, 'Bob', 'pass2'),
(3, 'Carol', 'pass3'),
(4, 'Dave', 'pass4'),
(5, 'Eve', 'pass5'),
(6, 'Frank', 'pass6'),
(7, 'Grace', 'pass7'),
(8, 'Heidi', 'pass8'),
(9, 'Ivan', 'pass9'),
(10, 'Judy', 'pass10'),
(11, 'Mallory', 'pass11'),
(12, 'Oscar', 'pass12'),
(13, 'Peggy', 'pass13'),
(14, 'Sybil', 'pass14'),
(15, 'Trent', 'pass15'),
(16, 'Victor', 'pass16'),
(17, 'Walter', 'pass17'),
(18, 'Yvonne', 'pass18'),
(19, 'Zara', 'pass19'),
(20, 'Quentin', 'pass20'),
(21, 'Nina', 'pass21'),
(22, 'Liam', 'pass22'),
(23, 'Sophia', 'pass23'),
(24, 'Lucas', 'pass24');

INSERT INTO Stats (id, user, wins, gamesplayed, elo) VALUES
(1, 1, 12, 30, 1520),
(2, 2, 8, 22, 1480),
(3, 3, 5, 18, 1425),
(4, 4, 9, 25, 1490),
(5, 5, 4, 12, 1400),
(6, 6, 6, 20, 1450),
(7, 7, 7, 21, 1470),
(8, 8, 3, 10, 1380),
(9, 9, 2, 9, 1350),
(10, 10, 8, 23, 1500),
(11, 11, 11, 29, 1550),
(12, 12, 5, 16, 1430),
(13, 13, 6, 18, 1445),
(14, 14, 3, 14, 1395),
(15, 15, 9, 27, 1515),
(16, 16, 4, 15, 1410),
(17, 17, 10, 28, 1540),
(18, 18, 6, 17, 1460),
(19, 19, 2, 11, 1370),
(20, 20, 5, 13, 1420),
(21, 21, 7, 18, 1485),
(22, 22, 3, 12, 1390),
(23, 23, 8, 20, 1505),
(24, 24, 4, 14, 1415);

INSERT INTO Gameslog (id, date) VALUES
(0, '2025-01-10 13:00:00'),
(1, '2025-02-02 14:00:00'),
(2, '2024-01-09 15:00:00'),
(3, '2025-01-09 15:00:00'),
(4, '2025-03-02 16:00:00'),
(5, '2025-01-08 17:00:00'),
(6, '2025-04-02 18:00:00'),
(7, '2025-01-07 19:00:00'),
(8, '2025-05-02 20:00:00'),
(9, '2025-01-06 21:00:00');

INSERT INTO Playerlog (gameid, playerid, elo, elodiff) VALUES
-- Game 0
(0, 1, 1520, +15),
(0, 2, 1480, -5),
(0, 3, 1425, -3),
(0, 4, 1490, -7),

-- Game 1
(1, 5, 1400, +10),
(1, 6, 1450, -4),
(1, 7, 1470, -2),
(1, 8, 1380, -4),

-- Game 2
(2, 9, 1350, +12),
(2, 10, 1500, -6),
(2, 11, 1550, -3),
(2, 12, 1430, -3),

-- Game 3
(3, 13, 1445, +14),
(3, 14, 1395, -8),
(3, 15, 1515, -5),
(3, 16, 1410, -1),

-- Game 4
(4, 17, 1540, +20),
(4, 18, 1460, -6),
(4, 19, 1370, -10),
(4, 20, 1420, -4),

-- Game 5
(5, 21, 1485, +5),
(5, 22, 1390, -3),
(5, 23, 1505, -2),
(5, 24, 1415, 0),

-- Game 6
(6, 1, 1535, +10),
(6, 5, 1410, -8),
(6, 9, 1362, -5),
(6, 13, 1455, +3),

-- Game 7
(7, 2, 1475, +6),
(7, 6, 1440, -3),
(7, 10, 1494, -4),
(7, 14, 1390, +1),

-- Game 8
(8, 3, 1420, +3),
(8, 7, 1468, -2),
(8, 11, 1547, -3),
(8, 15, 1520, +2),

-- Game 9
(9, 4, 1483, +7),
(9, 8, 1385, -2),
(9, 12, 1426, -3),
(9, 16, 1412, -2);

