CREATE DATABASE IF NOT EXISTS lecon_particuliere
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
USE lecon_particuliere;

-- users table
CREATE TABLE users (
  id_user         INT AUTO_INCREMENT PRIMARY KEY,
  first_name      VARCHAR(100)    NOT NULL,
  last_name       VARCHAR(100)    NOT NULL,
  email           VARCHAR(100)    NOT NULL UNIQUE,
  password_hash   VARCHAR(255)    NOT NULL,
  region          VARCHAR(100)    NOT NULL,
  cycle           VARCHAR(50),
  bio             TEXT,
  avatar_filename VARCHAR(255),
  avatar_data     LONGBLOB,
  role            ENUM('teacher','student') NOT NULL,
  created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP 
                                  ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- courses table
CREATE TABLE courses (
  id_course   INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(255) NOT NULL,
  description TEXT,
  category    VARCHAR(100),
  price       DECIMAL(10,2)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- link teachers to the courses they teach
CREATE TABLE teacher_courses (
  id_user   INT NOT NULL,
  id_course INT NOT NULL,
  PRIMARY KEY (id_user, id_course),
  FOREIGN KEY (id_user)   REFERENCES users(id_user)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_course) REFERENCES courses(id_course)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- reservations for course sessions
CREATE TABLE reservations (
  id_reservation INT AUTO_INCREMENT PRIMARY KEY,
  id_course      INT NOT NULL,
  date_hour      DATETIME NOT NULL,
  status         ENUM('confirm','canceled','on hold')
                   NOT NULL DEFAULT 'on hold',
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_course) REFERENCES courses(id_course)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- link students to their reservations
CREATE TABLE student_reservations (
  id_user        INT NOT NULL,
  id_reservation INT NOT NULL,
  PRIMARY KEY (id_user, id_reservation),
  FOREIGN KEY (id_user)        REFERENCES users(id_user)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_reservation) REFERENCES reservations(id_reservation)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- opinions (reviews), linked to both student and course
CREATE TABLE opinions (
  id_opinion    INT AUTO_INCREMENT PRIMARY KEY,
  id_user       INT NOT NULL,
  id_course     INT NOT NULL,
  stars         TINYINT NOT NULL CHECK (stars BETWEEN 1 AND 5),
  comment       TEXT,
  date_opinion  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_user)   REFERENCES users(id_user)
    ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (id_course) REFERENCES courses(id_course)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
