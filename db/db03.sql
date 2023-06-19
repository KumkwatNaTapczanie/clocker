ALTER TABLE `Task` DROP FOREIGN KEY `Task_ibfk_1` ;

ALTER TABLE `Task` ADD FOREIGN KEY ( `userId` ) REFERENCES `User` (
`id`
) ON DELETE CASCADE ;

ALTER TABLE `Task` DROP FOREIGN KEY `Task_ibfk_2` ;

ALTER TABLE `Task` ADD FOREIGN KEY ( `projectId` ) REFERENCES `Project` (
`id`
) ON DELETE SET NULL ;


ALTER TABLE `Client` DROP FOREIGN KEY `Client_ibfk_1` ;

ALTER TABLE `Client` ADD FOREIGN KEY ( `userId` ) REFERENCES `User` (
`id`
) ON DELETE CASCADE ;


ALTER TABLE `Project` DROP FOREIGN KEY `Project_ibfk_1` ;

ALTER TABLE `Project` ADD FOREIGN KEY ( `userId` ) REFERENCES `User` (
`id`
) ON DELETE CASCADE ;

ALTER TABLE `Project` DROP FOREIGN KEY `Project_ibfk_2` ;

ALTER TABLE `Project` ADD FOREIGN KEY ( `clientId` ) REFERENCES `Client` (
`id`
) ON DELETE SET NULL ;