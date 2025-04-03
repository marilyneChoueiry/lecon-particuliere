# Introduction
Ce projet offre aux eleves la possibilite de reserver facilement des cours avec des enseignants qualifies pres de chez eux.De leur cote ,les enseignants peuvent proposer leurs cours et gerer leur reservations.

# Base de données
## User
```SQL
CREATE TABLE student (
    id int primary key auto_increment, 
    name varchar(255) not null
)
```
## Teacher
```SQL
CREATE TABLE teacher (
    id int primary key auto_increment, 
    ...
)
```