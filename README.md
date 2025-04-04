# Introduction
Ce projet offre aux eleves la possibilite de reserver facilement des cours avec des enseignants qualifies pres de chez eux.De leur cote ,les enseignants peuvent proposer leurs cours et gerer leur reservations.

# Base de données
## user 
```SQL
CREATE TABLE Utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100)not null
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255),
    role ENUM('enseignant', 'élève'),
    
);
```
## teacher  
```SQL
CREATE TABLE teacher (
    id_enseignant INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT UNIQUE,
    bio TEXT,
    experience INT,
    
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) ON
 );
```
## student 
```SQL

CREATE TABLE student (
    id_eleve INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT UNIQUE,
    FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur) 
);
```
## cours 
```SQL

CREATE TABLE Cours (
    id_cours INT AUTO_INCREMENT PRIMARY KEY,
    id_enseignant INT,
    titre VARCHAR(255),
    description TEXT,
    categorie VARCHAR(100),
    tarif DECIMAL(10,2),
    FOREIGN KEY (id_enseignant) REFERENCES Enseignant(id_enseignant)
);
```
## reservation 
```SQL
CREATE TABLE Reservation (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    id_cours INT,
    id_eleve INT,
    date_heure DATETIME,
    statut ENUM('confirmé', 'annulé', 'en attente') DEFAULT 'en attente',
    FOREIGN KEY (id_cours) REFERENCES Cours(id_cours) ,
    FOREIGN KEY (id_eleve) REFERENCES Eleve(id_eleve) 
);
```
## avis 
```SQL
CREATE TABLE Avis (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    id_enseignant INT,
    id_eleve INT,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    date_avis TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_enseignant) REFERENCES Enseignant(id_enseignant) ,
    FOREIGN KEY (id_eleve) REFERENCES Eleve(id_eleve) 
);







