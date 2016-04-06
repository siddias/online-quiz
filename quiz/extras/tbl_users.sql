CREATE TABLE `quizit`.`members` (
`userId` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key' ,
 `id` VARCHAR(15) NOT NULL COMMENT 'ID' ,
 `fname` VARCHAR(30) NOT NULL COMMENT 'First Name' ,
 `lname` VARCHAR(20) NOT NULL COMMENT 'Last Name' ,
 `email` VARCHAR(100) NOT NULL COMMENT 'Email Id' ,
 `pass` VARCHAR(100) NOT NULL COMMENT 'Password' ,
 `userType` ENUM('T','S') NOT NULL DEFAULT 'S' COMMENT 'Teacher/Student' ,
 `verified` ENUM('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Verification status' ,
 `tokenCode` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`userId`),
  UNIQUE (`id`),
  UNIQUE (`email`)) ENGINE = InnoDB;


  CREATE TABLE  `quizit`.`quizlist` ( `quizId` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key' , `userId` INT(10) NOT NULL COMMENT 'Foreign Key' , `duration` INT(10) NOT NULL COMMENT 'Time for Quiz' , PRIMARY KEY (`quizId`), CONSTRAINT members_fk FOREIGN KEY(`userId`) REFERENCES `members`(`userId`) ON DELETE CASCADE )ENGINE = InnoDB
