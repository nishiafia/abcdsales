ALTER TABLE users
ADD COLUMN dialcode varchar(10) after email;

ALTER TABLE users RENAME COLUMN "phone" TO "telephone" varchar(10);
