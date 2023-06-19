CREATE TABLE `User` (
`id` INT NOT NULL AUTO_INCREMENT ,
`username` VARCHAR( 50 ) NOT NULL ,
`email` VARCHAR( 50 ) NOT NULL ,
`password` VARCHAR( 2048 ) NOT NULL ,
`role` VARCHAR( 25 ) NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = INNODB CHARACTER SET utf32 COLLATE utf32_general_ci;

CREATE TABLE `Task` (
`id` INT NOT NULL AUTO_INCREMENT ,
`userId` INT NOT NULL ,
`projectId` INT,
`title` VARCHAR( 200 ) NOT NULL ,
`startTime` DATETIME NOT NULL ,
`stopTime` DATETIME NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = INNODB CHARACTER SET utf32 COLLATE utf32_general_ci;
ALTER TABLE `Task` ADD INDEX ( `userId` );
ALTER TABLE `Task` ADD INDEX ( `projectId` );
ALTER TABLE `Task` ADD FOREIGN KEY ( `userId` ) REFERENCES `User` (
`id`
);

ALTER TABLE `Task` CHANGE `stopTime` `stopTime` DATETIME NULL;

CREATE TABLE `Project` (
`id` INT NOT NULL AUTO_INCREMENT ,
`userId` INT NOT NULL ,
`clientId` INT NULL ,
`projectName` VARCHAR( 50 ) NOT NULL,
`wage` DECIMAL( 10, 2 ) NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = INNODB CHARACTER SET utf32 COLLATE utf32_general_ci;
ALTER TABLE `Project` ADD INDEX ( `userId` );
ALTER TABLE `Project` ADD INDEX ( `clientId` );
ALTER TABLE `Project` ADD FOREIGN KEY ( `userId` ) REFERENCES `User` (
`id`
);

CREATE TABLE `Client` (
`id` INT NOT NULL AUTO_INCREMENT ,
`userId` INT NOT NULL ,
`clientName` VARCHAR( 50 ) NOT NULL,
PRIMARY KEY ( `id` )
) ENGINE = INNODB CHARACTER SET utf32 COLLATE utf32_general_ci;
ALTER TABLE `Client` ADD INDEX ( `userId` );
ALTER TABLE `Client` ADD FOREIGN KEY ( `userId` ) REFERENCES `User` (
`id`
);

ALTER TABLE `Project` ADD FOREIGN KEY ( `clientId` ) REFERENCES `Client` (
`id`
);

ALTER TABLE `Task` ADD FOREIGN KEY ( `projectId` ) REFERENCES `Project` (
`id`
);