# Introduction
Ce projet offre aux eleves la possibilite de reserver facilement des cours avec des enseignants qualifies pres de chez eux.De leur cote ,les enseignants peuvent proposer leurs cours et gerer leur reservations.

# Base de données
## user 
```SQL
CREATE TABLE User (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100)not null
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    bio text,
    role ENUM('teacher', 'student'),
    
);
```

## course 
```SQL

CREATE TABLE Course (
    id_course INT AUTO_INCREMENT PRIMARY KEY,
    id_teacher INT,
    title VARCHAR(255),
    description TEXT,
    category VARCHAR(100),
    price DECIMAL(10,2),
    FOREIGN KEY (id_teacher) REFERENCES Teacher (id_teacher)
);
```
## reservation 
```SQL
CREATE TABLE Reservation (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    id_course INT,
    id_student INT,
    date_hour DATETIME,
    statut ENUM('confirm', 'canceled', 'on hold') DEFAULT 'on hold',
    FOREIGN KEY (id_course) REFERENCES Course(id_cours) ,
    FOREIGN KEY (id_student) REFERENCES Student(id_student) 
);
```
## avis 
```SQL
CREATE TABLE opinion (
    id_opinion INT AUTO_INCREMENT PRIMARY KEY,
    id_teacher INT,
    id_student INT,
    stars INT CHECK (note BETWEEN 1 AND 5),
    comment TEXT,
    date_opinion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_teacher) REFERENCES Teacher(id_teacher) ,
    FOREIGN KEY (id_student) REFERENCES Student(id_student) 
);
```






