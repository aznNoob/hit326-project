START TRANSACTION;

USE northern_australian_times_db;

/*
----------------------
CREATING RECORDS
----------------------
*/
INSERT INTO `users` (`name`, `email`, `password`, `role`) 
VALUES ('Dylan Kennedy', 'dylan@example.com', 'password1', 'reader');

SET @last_user_id = LAST_INSERT_ID();

INSERT INTO `tags` (`tag`) 
VALUES ('Entertainment');

SET @last_tag_id = LAST_INSERT_ID();

INSERT INTO `articles` (`user_id`, `title`, `body`, `status`) 
VALUES (@last_user_id, 'Sample Article', 'This is a sample article.', 'published');

SET @last_article_id = LAST_INSERT_ID();

INSERT INTO `mapping_articles_tags` (`article_id`, `tag_id`) 
VALUES (@last_article_id, @last_tag_id);

/*
----------------------
READING RECORDS
----------------------
*/
SELECT * FROM `users` WHERE `id`=@last_user_id;

SELECT * FROM `tags` WHERE `id`=@last_tag_id;

SELECT * FROM `articles` WHERE `id`=@last_article_id;

SELECT * FROM `mapping_articles_tags` WHERE `article_id`=@last_article_id;

/*
----------------------
UPDATING RECORDS
----------------------
*/
UPDATE `users` 
SET `email`='dylankennedy@example.com' 
WHERE `id`=@last_user_id;

UPDATE `tags` 
SET `tag`='Updated Entertainment' 
WHERE `id`=@last_tag_id;

UPDATE `articles` 
SET `title`='Updated Article Title' 
WHERE `id`=@last_article_id;

UPDATE `mapping_articles_tags` 
SET `tag_id`= @last_tag_id 
WHERE `article_id`=@last_article_id;

/*
----------------------
DELETING RECORDS
----------------------
*/
DELETE FROM `mapping_articles_tags` 
WHERE `article_id`=@last_article_id;

DELETE FROM `articles` 
WHERE `id`=@last_article_id;

DELETE FROM `tags` 
WHERE `id`=@last_tag_id;

DELETE FROM `users` 
WHERE `id`=@last_user_id;

COMMIT;