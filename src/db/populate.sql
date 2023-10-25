-- Populate users table
INSERT INTO users (email, password, name, username, date_of_birth, reputation)
VALUES ('john.doe@example.com', 'password123', 'John Doe', 'john_doe', '1990-01-01', 100);

INSERT INTO users (email, password, name, username, date_of_birth, reputation)
VALUES ('jane.smith@example.com', 'password456', 'Jane Smith', 'jane_smith', '1992-06-15', 150);

-- Populate admins table
INSERT INTO admins DEFAULT VALUES;
INSERT INTO admins DEFAULT VALUES;

-- Populate categories table
INSERT INTO categories (name) VALUES ('Technology');
INSERT INTO categories (name) VALUES ('Health');

-- Populate tags table
INSERT INTO tags (name) VALUES ('AI');
INSERT INTO tags (name) VALUES ('VitaminD');

-- Populate posts table
INSERT INTO posts (title, description, authorID, created_at)
VALUES ('Introduction to AI', 'A beginner guide to understanding AI', 1, '2023-01-01');

INSERT INTO posts (title, description, authorID, created_at)
VALUES ('Benefits of Vitamin D', 'Understanding the benefits of Vitamin D', 2, '2023-02-01');

-- Populate comments table
INSERT INTO comments (postID, authorID, text)
VALUES (1, 2, 'Great introduction to AI!');

-- Populate category_posts (junction table)
INSERT INTO category_posts (categoryID, postID) VALUES (1, 1);
INSERT INTO category_posts (categoryID, postID) VALUES (2, 2);

-- Populate posts_tags (junction table)
INSERT INTO posts_tags (postID, tagID) VALUES (1, 1);
INSERT INTO posts_tags (postID, tagID) VALUES (2, 2);

-- Populate category_tags (junction table)
INSERT INTO category_tags (categoryID, tagID) VALUES (1, 1);
INSERT INTO category_tags (categoryID, tagID) VALUES (2, 2);

-- Populate reports table
INSERT INTO reports (name) VALUES ('Inappropriate Content');
INSERT INTO reports (name) VALUES ('Spam');

-- Populate blocks table
INSERT INTO blocks (adminID, userID, blocked_at, reason)
VALUES (1, 2, '2023-03-01', 'Violation of community guidelines');
