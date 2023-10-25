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
