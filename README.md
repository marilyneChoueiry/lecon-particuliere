# Introduction
Ce projet offre aux eleves la possibilite de reserver facilement des cours avec des enseignants qualifies pres de chez eux.De leur cote ,les enseignants peuvent proposer leurs cours et gerer leur reservations.

# Base de données
## user 
```SQL
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    region VARCHAR(100) NOT NULL,
    cycle VARCHAR(50),
    bio TEXT,
    file_name VARCHAR(255),
    file_data LONGBLOB,
    role ENUM('teacher', 'student') NOT NULL
);
```

## courses
```SQL
CREATE TABLE courses(
    id_user INT,
    id_course INT,
    PRIMARY KEY (id_user, id_course),
    FOREIGN KEY (id_user) REFERENCES users(id_user) ,
    FOREIGN KEY ( id_course) REFERENCES  Course(id_course)
)
```




## course 
```SQL

CREATE TABLE Course (
    id_course INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    category VARCHAR(100),
    price DECIMAL(10,2)
    
)
```
## studentReservation
```SQL
CREATE TABLE student_reservation(
    id_user INT,
     id_reservation INT,
    PRIMARY KEY (id_user,  id_reservation),
    FOREIGN KEY (id_user) REFERENCES users(id_user) ,
    FOREIGN KEY ( id_reservation) REFERENCES Reservation( id_reservation)
)
```
## reservation 
```SQL
CREATE TABLE Reservation (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    id_course INT,
    date_hour DATETIME,
    statut ENUM('confirm', 'canceled', 'on hold') DEFAULT 'on hold',
    FOREIGN KEY (id_course) REFERENCES Course(id_cours) 
   
)
```
##  
```SQL
CREATE TABLE opinion (
    id_opinion INT AUTO_INCREMENT PRIMARY KEY,
    stars INT CHECK (stars BETWEEN 1 AND 5),
    comment TEXT,
    date_opinion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
```






