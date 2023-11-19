-----------------------------------------
-- Use a specific schema and set it as default - lbaw2382.
-----------------------------------------
DROP SCHEMA IF EXISTS lbaw2382 CASCADE;
CREATE SCHEMA IF NOT EXISTS lbaw2382;
SET search_path TO lbaw2382;

-----------------------------------------
-- Drop any existing tables.
-----------------------------------------
DROP TABLE IF EXISTS blocks CASCADE;
DROP TABLE IF EXISTS reports CASCADE;
DROP TABLE IF EXISTS category_tags CASCADE;
DROP TABLE IF EXISTS posts_tags CASCADE;
DROP TABLE IF EXISTS category_posts CASCADE;
DROP TABLE IF EXISTS tags CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS posts CASCADE;
DROP TABLE IF EXISTS user_relationships CASCADE;
DROP TABLE IF EXISTS notifications CASCADE;
DROP TABLE IF EXISTS admins CASCADE;
DROP TABLE IF EXISTS users CASCADE;

--
CREATE DOMAIN Today AS DATE DEFAULT CURRENT_DATE;

--
CREATE TYPE Types AS ENUM ('Friend', 'Followed', 'Following', 'None');


-----------------------------------------
-- Create tables.
-----------------------------------------

-- users table
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    date_of_birth DATE,
    reputation INT DEFAULT 0
);

-- admins table
CREATE TABLE admins (
    id SERIAL PRIMARY KEY
);

-- notifications table
CREATE TABLE notifications (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id),
    text VARCHAR(255) NOT NULL,
    created_at DATE,
    is_read BOOLEAN DEFAULT FALSE,
    post_id INT,
    comment_id INT
);

-- user_relationships table
CREATE TABLE user_relationships (
    id SERIAL PRIMARY KEY,
    user_id1 INT REFERENCES users(id),
    user_id2 INT REFERENCES users(id),
    relationship_type VARCHAR(255),
    UNIQUE(user_id1, user_id2)
);

-- comments table
CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    post_id INT,
    user_id INT REFERENCES users(id),
    parent_comment_id INT REFERENCES comments(id),
    text VARCHAR(255) NOT NULL,
    upvotes INT DEFAULT 0,
    downvotes INT DEFAULT 0
);

-- posts table
CREATE TABLE posts (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    user_id INT REFERENCES users(id),
    upvotes INT DEFAULT 0,
    downvotes INT DEFAULT 0,
    created_at DATE
);

-- adding the ref for comments to posts after the posts table is defined (to avoid circular references)
ALTER TABLE comments ADD FOREIGN KEY (post_id) REFERENCES posts(id);

-- categories table
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- tags table
CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- category_posts (junction table)
CREATE TABLE category_posts (
    category_id INT REFERENCES categories(id),
    post_id INT REFERENCES posts(id),
    PRIMARY KEY (category_id, post_id)
);

--  (junction table)
CREATE TABLE posts_tags (
    post_id INT REFERENCES posts(id),
    tag_id INT REFERENCES tags(id),
    PRIMARY KEY (post_id, tag_id)
);

-- (junction table)
CREATE TABLE category_tags (
    category_id INT REFERENCES categories(id),
    tag_id INT REFERENCES tags(id),
    PRIMARY KEY (category_id, tag_id)
);

-- reports table
CREATE TABLE reports (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- blocks table
CREATE TABLE blocks (
    id SERIAL PRIMARY KEY,
    admin_id INT REFERENCES admins(id),
    user_id INT REFERENCES users(id),
    blocked_at DATE,
    reason VARCHAR(255),
    UNIQUE(admin_id, user_id)
);

-----------------------------------------
-- INDEXES
-----------------------------------------
CREATE INDEX idx_users_email 
ON users USING btree(email);


CREATE INDEX idx_posts_created_at 
ON posts USING btree(created_at);
CLUSTER posts USING idx_posts_created_at;

CREATE INDEX idx_comments_post_id ON comments 
USING btree(post_id);

-- FULL-TEXT SEARCH
/*
CREATE INDEX idx_posts_fts ON posts 
USING 
gin(to_tsvector('english', setweight(title, 'A') || ' ' || setweight(description, 'B')));
*/


-----------------------------------------
-- TRIGGERS and UDFs
-----------------------------------------

--- UDFs - most not  working 

-- 
CREATE OR REPLACE FUNCTION update_fts_index() RETURNS TRIGGER AS $$
BEGIN
    -- need to fix this
    RAISE NOTICE 'FTS index would be updated for post %', NEW.id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION notify_followers_newpost() RETURNS TRIGGER AS $$
DECLARE 
    follower_id INT;
BEGIN
    FOR follower_id IN (
        SELECT user_id1 
        FROM user_relationships 
        WHERE user_id2 = NEW.user_id AND relationship_type = 'follower'
    ) LOOP
        INSERT INTO notifications (user_id, text, created_at, post_id)
        VALUES (follower_id, 'A user you follow has posted something new!', CURRENT_DATE, NEW.post_id);
    END LOOP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- 
CREATE OR REPLACE FUNCTION create_audit_log_entry() RETURNS TRIGGER AS $$
BEGIN
    -- 
    RAISE NOTICE 'ALE: User % has been updated', NEW.user_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE TRIGGER trg_update_fts_index 
AFTER INSERT OR UPDATE ON posts 
FOR EACH ROW EXECUTE FUNCTION update_fts_index();

CREATE TRIGGER trg_notification_newpost 
AFTER INSERT ON posts 
FOR EACH ROW 
EXECUTE FUNCTION notify_followers_newpost();

CREATE TRIGGER trg_audit_log
AFTER UPDATE ON users 
FOR EACH ROW EXECUTE FUNCTION create_audit_log_entry();


-----------------------------------------
-- Insert values.
-----------------------------------------

INSERT INTO users VALUES (
    DEFAULT,
    'admin@example.com',  
    '$2y$10$HfzIhGCCaxqyaIdGgjARSuOKAcm1Uy82YfLuNaajn6JrjLWy9Sj/W',
    'John Doe',
    'admin'
); -- Password is 1234. Generated using Hash::make('1234')


--INSERT INTO users (email, password, name, username, date_of_birth, reputation)
--VALUES ('john.doe@example.com', 'password123', 'John Doe', 'john_doe', '1990-01-01', 100);

INSERT INTO users (email, password, name, username, date_of_birth, reputation)
VALUES ('jane.smith@example.com', '$2a$12$vYNb4B0GDBSxZrT7IJG9UuYDoVjHHOVjsEyR27sthqqu.aNSSyN5W', 'Jane Smith', 'jane_smith', '1992-06-15', 150); -- Password is 0000. Generated using Hash::make('0000')


INSERT INTO admins DEFAULT VALUES;
INSERT INTO admins DEFAULT VALUES;


INSERT INTO categories (name) VALUES ('Technology');
INSERT INTO categories (name) VALUES ('Health');

INSERT INTO tags (name) VALUES ('AI');
INSERT INTO tags (name) VALUES ('VitaminD');


INSERT INTO posts (id, title, description, user_id, created_at)
VALUES (1, 'Introduction to AI', 'A beginner guide to understanding AI', 1, '2023-01-01');

INSERT INTO posts (id, title, description, user_id, created_at)
VALUES (2, 'Benefits of Vitamin D', 'Understanding the benefits of Vitamin D', 2, '2023-02-01');


INSERT INTO comments (id, post_id, user_id, text)
VALUES (1, 1, 2, 'Great introduction to AI!');

INSERT INTO comments (id, post_id, user_id, text)
VALUES (2, 2, 1, 'Great introduction to Vitamin benefits!');

INSERT INTO comments (id, post_id, user_id, text)
VALUES (3, 2, 2, 'Thanks!');



INSERT INTO category_posts (category_id, post_id) VALUES (1, 1);
INSERT INTO category_posts (category_id, post_id) VALUES (2, 2);


INSERT INTO posts_tags (post_id, tag_id) VALUES (1, 1);
INSERT INTO posts_tags (post_id, tag_id) VALUES (2, 2);


INSERT INTO category_tags (category_id, tag_id) VALUES (1, 1);
INSERT INTO category_tags (category_id, tag_id) VALUES (2, 2);


INSERT INTO reports (name) VALUES ('Inappropriate Content');
INSERT INTO reports (name) VALUES ('Spam');


INSERT INTO blocks (admin_id, user_id, blocked_at, reason)
VALUES (1, 2, '2023-03-01', 'Violation of community guidelines');
