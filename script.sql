CREATE DATABASE BlogPress;

CREATE DATABASE add;

use BlogPress;
CREATE Table auteur (
    user_id INT(11) NOT NULL AUTO_INCREMENT,
    user_fname VARCHAR(50) NOT NULL,
    user_lname VARCHAR(50) NOT NULL,
    email VARCHAR(70) NOT NULL,
    password varchar(50) NOT NULL,
    PRIMARY key(user_id)
    
);

CREATE Table articles (
    article_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    article_img VARCHAR(50) NOT NULL,
    article_titre VARCHAR(50) NOT NULL,
    article_categorie VARCHAR(50) NOT NULL,
    article_content TEXT NOT NULL,
    article_views INT(11),
    article_likes INT(11),
    article_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (article_id),
    FOREIGN KEY (user_id) REFERENCES Utilisateurs(user_id)
);

CREATE TABLE comments (
    comment_id INT(11) NOT NULL AUTO_INCREMENT,
    article_id INT(11) NOT NULL,
    username INT(11) NOT NULL,
    content TEXT,
    comment_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (comment_id),
    FOREIGN KEY (article_id) REFERENCES articles(article_id) on delete CASCADE
);


RENAME TABLE Utilisateurs TO Auteur;

ALTER TABLE articles
MODIFY article_img VARCHAR(500);


SELECT * FROM users WHERE email='example@example.com';
-- @block
DESCRIBE articles;

DELETE  FROM comments WHERE comment_id = 1;

ALTER TABLE comments RENAME COLUMN user_id TO username;

DROP TABLE comments;

ALTER TABLE comments MODIFY username VARCHAR(255);

SELECT * FROM articles ;

UPDATE articles
SET article_views = 0
WHERE article_views IS NULL;


ALTER TABLE articles
MODIFY article_views  INT(11) DEFAULT 0;