INSERT INTO `User` (
`id` ,
`username` ,
`email` ,
`password` ,
`role`
)
VALUES (
NULL , 'Robert', 'rob@mail.com', 'rob', 'user'
), (
NULL , 'John', 'john@mail.com', 'john', 'user'
), (
NULL , 'Bobert', 'bob@mail.com', 'bob', 'user'
);

INSERT INTO `Client` (
`id` ,
`userId` ,
`clientName`
)
VALUES (
NULL , '1', 'ZUT'
), (
NULL , '1', 'WI3'
), (
NULL , '2', 'Skynet'
), (
NULL , '2', 'Evil Inc.'
), (
NULL , '3', 'A.C.M.E.'
);

INSERT INTO `Project` (
`id` ,
`userId` ,
`clientId` ,
`projectName` ,
`wage`
)
VALUES (
NULL , '1', '1', 'PHP', '10.50'
), (
NULL , '3', '5', 'VIP Project', '13.40'
), (
NULL , '3', '5', 'Sample', '34.67'
), (
NULL , '3', '5', 'Dummy', '12.30'
), (
NULL , '1', '1', 'Hello', '11.23'
), (
NULL , '1', '2', 'Bye', '9.80'
), (
NULL , '2', '4', 'Hi', '3.40'
), (
NULL , '2', '4', 'Nope', '17.68'
), (
NULL , '2', '3', 'Yup', '5.30'
), (
NULL , '3', '5', 'Why', '14.30'
);

UPDATE `Project` SET `clientId` = NULL WHERE `Project`.`id` =2;


INSERT INTO `Task` (
`id` ,
`userId` ,
`projectId` ,
`title` ,
`startTime` ,
`stopTime`
)
VALUES (
NULL , '3', '2', 'Gram w LOL-a', '2021-12-28 16:17:52', '2021-12-29 16:17:57'
), (
NULL , '3', NULL , 'Gram w COD', '2021-12-29 16:18:00', '2021-12-31 16:18:03'
), (
NULL , '3', '3', 'Gram w Minecrafta', '2021-12-16 16:18:06', NULL
), (
NULL , '1', '6', 'Nauka PHP', '2021-12-07 16:18:10', '2021-12-31 16:18:12'
), (
NULL , '1', '1', 'Nauka JS', '2021-12-16 16:18:17', '2021-12-31 16:18:19'
), (
NULL , '1', '5', 'Nauka HTML', '2021-12-06 16:18:22', NULL
), (
NULL , '2', NULL , 'Spanie', '2021-12-18 16:18:25', '2021-12-25 16:18:28'
), (
NULL , '2', '8', 'Jedzenie', '2021-12-15 16:18:31', '2021-12-22 16:18:34'
), (
NULL , '2', '9', 'Płacz', '2021-12-20 16:18:36', '2021-12-30 16:18:38'
), (
NULL , '2', '7', 'Atak paniki', '2021-12-26 16:18:42', NULL
);

INSERT INTO `User` (
`id` ,
`username` ,
`email` ,
`password` ,
`role`
)
VALUES (
NULL , 'Martynka', 'martynka@mail.com', 'mar', 'admin'
);