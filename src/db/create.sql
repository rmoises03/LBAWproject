SET search_path TO lbaw2382;

CREATE TABLE users (
    userID SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL, -- should be hashed
    email VARCHAR(255) UNIQUE,
    reputation INT DEFAULT 0
);


CREATE TABLE admins (
    adminID SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL -- should be hashed
);




CREATE TABLE posts (
    postID SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    authorID INT REFERENCES users(userID),
    upvotes INT DEFAULT 0,
    downvotes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE comments (
    commentID SERIAL PRIMARY KEY,
    postID INT REFERENCES posts(postID),
    authorID INT REFERENCES users(userID),
    parent_commentID INT REFERENCES comments(commentID),
    text TEXT NOT NULL
);


CREATE TABLE tags (
    tagID SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);


CREATE TABLE categories (
    categoryID SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);


CREATE TABLE reports (
    reportID SERIAL PRIMARY KEY,
    name TEXT NOT NULL
);

--  M-M relationship between posts and tags
CREATE TABLE post_tags (
    postID INT REFERENCES posts(postID),
    tagID INT REFERENCES tags(tagID),
    PRIMARY KEY (postID, tagID)
);

--  M-M relationship between posts and categories
CREATE TABLE category_tags (
    categoryID INT REFERENCES categories(categoryID),
    tagID INT REFERENCES tags(tagID),
    PRIMARY KEY (categoryID, tagID)
);


