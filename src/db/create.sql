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

-- users table
CREATE TABLE users (
    userID SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    date_of_birth DATE,
    reputation INT DEFAULT 0
);

-- admins table
CREATE TABLE admins (
    adminID SERIAL PRIMARY KEY
);

-- notifications table
CREATE TABLE notifications (
    notificationID SERIAL PRIMARY KEY,
    userID INT REFERENCES users(userID),
    text VARCHAR(255) NOT NULL,
    created_at DATE,
    is_read BOOLEAN DEFAULT FALSE,
    postID INT,
    commentID INT
);

-- user_relationships table
CREATE TABLE user_relationships (
    relationshipID SERIAL PRIMARY KEY,
    userID1 INT REFERENCES users(userID),
    userID2 INT REFERENCES users(userID),
    relationship_type VARCHAR(255),
    UNIQUE(userID1, userID2)
);

-- comments table
CREATE TABLE comments (
    commentID SERIAL PRIMARY KEY,
    postID INT,
    authorID INT REFERENCES users(userID),
    parent_commentID INT REFERENCES comments(commentID),
    text VARCHAR(255) NOT NULL,
    upvotes INT DEFAULT 0,
    downvotes INT DEFAULT 0
);

-- posts table
CREATE TABLE posts (
    postID SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    authorID INT REFERENCES users(userID),
    upvotes INT DEFAULT 0,
    downvotes INT DEFAULT 0,
    created_at DATE
);

-- adding the ref for comments to posts after the posts table is defined (to avoid circular references)
ALTER TABLE comments ADD FOREIGN KEY (postID) REFERENCES posts(postID);

-- categories table
CREATE TABLE categories (
    categoryID SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- tags table
CREATE TABLE tags (
    tagID SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE
);

-- category_posts (junction table)
CREATE TABLE category_posts (
    categoryID INT REFERENCES categories(categoryID),
    postID INT REFERENCES posts(postID),
    PRIMARY KEY (categoryID, postID)
);

--  (junction table)
CREATE TABLE posts_tags (
    postID INT REFERENCES posts(postID),
    tagID INT REFERENCES tags(tagID),
    PRIMARY KEY (postID, tagID)
);

-- (junction table)
CREATE TABLE category_tags (
    categoryID INT REFERENCES categories(categoryID),
    tagID INT REFERENCES tags(tagID),
    PRIMARY KEY (categoryID, tagID)
);

-- reports table
CREATE TABLE reports (
    reportID SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- blocks table
CREATE TABLE blocks (
    blockID SERIAL PRIMARY KEY,
    adminID INT REFERENCES admins(adminID),
    userID INT REFERENCES users(userID),
    blocked_at DATE,
    reason VARCHAR(255),
    UNIQUE(adminID, userID)
);


-----------------------------------------
-- INDEXES
-----------------------------------------
CREATE INDEX idx_users_email 
ON users USING btree(email);


CREATE INDEX idx_posts_created_at 
ON posts USING btree(created_at);
CLUSTER posts USING idx_posts_created_at;

CREATE INDEX idx_comments_postID ON comments 
USING btree(postID);

-- FULL-TEXT SEARCH

CREATE INDEX idx_posts_fts ON posts 
USING 
gin(to_tsvector('english',setweight(title, 'A') || ' ' || setweight(description, 'B')));

-----------------------------------------
-- TRIGGERS and UDFs
-----------------------------------------


--- UDFs - most not  working 

-- 
CREATE OR REPLACE FUNCTION update_fts_index() RETURNS TRIGGER AS $$
BEGIN
    -- need to fix this
    RAISE NOTICE 'FTS index would be updated for post %', NEW.postID;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION notify_followers_newpost() RETURNS TRIGGER AS $$
DECLARE 
    follower_id INT;
BEGIN
    FOR follower_id IN (
        SELECT userID1 
        FROM user_relationships 
        WHERE userID2 = NEW.authorID AND relationship_type = 'follower'
    ) LOOP
        INSERT INTO notifications (userID, text, created_at, postID)
        VALUES (follower_id, 'A user you follow has posted something new!', CURRENT_DATE, NEW.postID);
    END LOOP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- 
CREATE OR REPLACE FUNCTION create_audit_log_entry() RETURNS TRIGGER AS $$
BEGIN
    -- 
    RAISE NOTICE 'ALE: User % has been updated', NEW.userID;
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





