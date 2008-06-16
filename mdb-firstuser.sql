-- Creates an administrative user
-- username: root
-- password: root
INSERT INTO users (username,password,privilege) VALUES ('root',MD5('root'),'1');
