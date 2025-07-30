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




