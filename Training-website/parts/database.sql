
CREATE DATABASE Training;
USE Training;

CREATE TABLE user(
     id INT PRIMARY KEY auto_increment,
     username VARCHAR(40) UNIQUE,
     password VARCHAR(40),
     desplay_name VARCHAR(40),
     last_hit DATE,
     user_type VARCHAR(10)
);

ALTER TABLE user auto_increment=1;

INSERT INTO user(username, password, desplay_name, user_type)
VALUES( "bahaa", SHA1("bahaa123"), "", "student");

INSERT INTO user(username, password, desplay_name, user_type)
VALUES( "ali", SHA1("ali123"), "", "student");

INSERT INTO user(username, password, desplay_name, user_type)
VALUES( "exalt", SHA1("exalt123"), "", "company");


CREATE TABLE city(
     id INT PRIMARY KEY auto_increment,
     name VARCHAR(40),
     country VARCHAR(40)
);

ALTER TABLE city auto_increment=1;

INSERT INTO city(name, country) VALUES("Ramallah", "Palestine");
INSERT INTO city(name, country) VALUES("Jerusalem", "Palestine");
INSERT INTO city(name, country) VALUES("Hebron", "Palestine");
INSERT INTO city(name, country) VALUES("Nablus", "Palestine");
INSERT INTO city(name, country) VALUES("Bethlehem", "Palestine");

CREATE TABLE student(
      id INT PRIMARY KEY auto_increment,
      name VARCHAR(40),
      city_id int,
      email VARCHAR(50) UNIQUE,
      tel VARCHAR(20) UNIQUE,
      university VARCHAR(30),
      major VARCHAR(30),
      projects VARCHAR(60),
      interests VARCHAR(60),
      photo_path VARCHAR(60),
      user_id int,
      
      FOREIGN KEY(city_id) REFERENCES city(id) 
      ON DELETE CASCADE
      ON UPDATE CASCADE,
      
      FOREIGN KEY(user_id) REFERENCES user(id) 
      ON DELETE CASCADE
      ON UPDATE CASCADE
);


ALTER TABLE student auto_increment=1;
INSERT INTO student(name, city_id, email, tel, university, major, projects, interests, photo_path, user_id)
VALUES("bahaa", 1, "bahaaassad286@gemail.com", "+970597785434", "berzeit university", "physics", "Library database project", "web development", "images/my-photo.png", 1);

INSERT INTO student(name, city_id, email, tel, university, major, projects, interests, photo_path, user_id)
VALUES("ali", 2, "ali123@gemail.com", "+970568993451", "berzeit university", "computer science", "Library database project", "web development", "images/student.png", 2);

CREATE TABLE company(
      id INT PRIMARY KEY auto_increment,
      name VARCHAR(40),
      city_id int,
      email VARCHAR(50) UNIQUE,
      tel VARCHAR(20) UNIQUE,
      position_count int,
      position_details VARCHAR(100),
      logo_path VARCHAR(60),
      user_id int,
      
      FOREIGN KEY(city_id) REFERENCES city(id) 
      ON DELETE CASCADE
      ON UPDATE CASCADE,
      
      FOREIGN KEY(user_id) REFERENCES user(id) 
      ON DELETE CASCADE
      ON UPDATE CASCADE
);

INSERT INTO company(name, city_id, email, tel, position_count, position_details, logo_path, user_id)
VALUES("exalt", 1, "exalt@gmail.com", "+972-22965740", 5, "Al Maison Ramallah, Palestine", "images/Exalt-company-logo.png", 3);

CREATE TABLE student_applications(
     id INT PRIMARY KEY auto_increment,
     student_id int UNIQUE,
     company_id int UNIQUE,
     apply_date DATE,
     requested_by_user_id int,
     application_status VARCHAR(10) DEFAULT "sent",
     
     FOREIGN KEY(student_id) REFERENCES student(id)
     ON DELETE CASCADE
     ON UPDATE CASCADE,
     
     FOREIGN KEY(company_id) REFERENCES company(id)
     ON DELETE CASCADE
     ON UPDATE CASCADE,
     
     FOREIGN KEY(requested_by_user_id) REFERENCES user(id)
     ON DELETE CASCADE
     ON UPDATE CASCADE
);

ALTER TABLE student_applications AUTO_INCREMENT  = 1;