
INSERT INTO users (email, password, name, username, date_of_birth, reputation)
VALUES ('john.doe@example.com', 'password123', 'John Doe', 'john_doe', '1990-01-01', 100);

INSERT INTO users (email, password, name, username, date_of_birth, reputation)
VALUES ('jane.smith@example.com', 'password456', 'Jane Smith', 'jane_smith', '1992-06-15', 150);


INSERT INTO admins DEFAULT VALUES;
INSERT INTO admins DEFAULT VALUES;


INSERT INTO categories (name) VALUES ('Technology');
INSERT INTO categories (name) VALUES ('Health');

INSERT INTO tags (name) VALUES ('AI');
INSERT INTO tags (name) VALUES ('VitaminD');


INSERT INTO posts (title, description, authorID, created_at)
VALUES ('Introduction to AI', 'A beginner guide to understanding AI', 1, '2023-01-01');

INSERT INTO posts (title, description, authorID, created_at)
VALUES ('Benefits of Vitamin D', 'Understanding the benefits of Vitamin D', 2, '2023-02-01');


INSERT INTO comments (postID, authorID, text)
VALUES (1, 2, 'Great introduction to AI!');


INSERT INTO category_posts (categoryID, postID) VALUES (1, 1);
INSERT INTO category_posts (categoryID, postID) VALUES (2, 2);


INSERT INTO posts_tags (postID, tagID) VALUES (1, 1);
INSERT INTO posts_tags (postID, tagID) VALUES (2, 2);


INSERT INTO category_tags (categoryID, tagID) VALUES (1, 1);
INSERT INTO category_tags (categoryID, tagID) VALUES (2, 2);


INSERT INTO reports (name) VALUES ('Inappropriate Content');
INSERT INTO reports (name) VALUES ('Spam');


INSERT INTO blocks (adminID, userID, blocked_at, reason)
VALUES (1, 2, '2023-03-01', 'Violation of community guidelines');
