
USE db_s2_ETU003958;

CREATE TABLE e_membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    date_de_naissance DATE,
    genre VARCHAR (50) ,
    email VARCHAR(100) UNIQUE,
    ville VARCHAR(50) ,
    mdp VARCHAR(50) NOT NULL,
    id_image_profil VARCHAR(100) 
);


CREATE TABLE e_categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL
);

CREATE TABLE e_objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100) NOT NULL,
    id_categorie INT NOT NULL,
    id_membre INT NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES e_categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES e_membre(id_membre)
);

CREATE TABLE e_images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    nom_image VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_objet) REFERENCES e_objet(id_objet)
);

CREATE TABLE e_emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    id_membre INT NOT NULL,
    date_emprunt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_retour DATETIME,
    FOREIGN KEY (id_objet) REFERENCES e_objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES e_membre(id_membre)
);

INSERT INTO e_membre (nom, date_de_naissance, genre, email, ville, mdp, id_image_profil) VALUES
('Alice Dupont', '1990-05-12', 'Femme', 'alice.dupont@email.com', 'Paris', 'mdp1', 'img1.jpg'),
('Bob Martin', '1985-08-23', 'Homme', 'bob.martin@email.com', 'Lyon', 'mdp2', 'img2.jpg'),
('Claire Petit', '1992-11-03', 'Femme', 'claire.petit@email.com', 'Marseille', 'mdp3', 'img3.jpg'),
('David Leroy', '1988-02-17', 'Homme', 'david.leroy@email.com', 'Toulouse', 'mdp4', 'img4.jpg');

INSERT INTO e_categorie_objet (nom_categorie) VALUES
('esthétique'),
('bricolage'),
('mécanique'),
('cuisine');

INSERT INTO e_objet (nom_objet, id_categorie, id_membre) VALUES
('Sèche-cheveux', 1, 1),
('Trousse de maquillage', 1, 1),
('Perceuse', 2, 1),
('Tournevis', 2, 1),
('Clé à molette', 3, 1),
('Pompe à vélo', 3, 1),
('Mixeur', 4, 1),
('Casserole', 4, 1),
('Batteur électrique', 4, 1),
('Lisseur', 1, 1);

INSERT INTO e_objet (nom_objet, id_categorie, id_membre) VALUES
('Tondeuse', 1, 2),
('Pinceau', 1, 2),
('Marteau', 2, 2),
('Scie', 2, 2),
('Tournevis électrique', 2, 2),
('Clé dynamométrique', 3, 2),
('Pompe à main', 3, 2),
('Poêle', 4, 2),
('Grille-pain', 4, 2),
('Fouet', 4, 2);

INSERT INTO e_objet (nom_objet, id_categorie, id_membre) VALUES
('Brosse à cheveux', 1, 3),
('Palette de maquillage', 1, 3),
('Perceuse sans fil', 2, 3),
('Visseuse', 2, 3),
('Clé plate', 3, 3),
('Pompe à pied', 3, 3),
('Robot de cuisine', 4, 3),
('Cocotte', 4, 3),
('Batteur', 4, 3),
('Fer à lisser', 1, 3);

INSERT INTO e_objet (nom_objet, id_categorie, id_membre) VALUES
('Rasoir', 1, 4),
('Crème coiffante', 1, 4),
('Scie sauteuse', 2, 4),
('Tournevis plat', 2, 4),
('Clé Allen', 3, 4),
('Pompe électrique', 3, 4),
('Blender', 4, 4),
('Poêle antiadhésive', 4, 4),
('Grille-pain inox', 4, 4),
('Brosse soufflante', 1, 4);

INSERT INTO e_emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2024-06-01 10:00:00', '2024-06-05 10:00:00'),
(12, 1, '2024-06-02 11:00:00', '2024-06-06 11:00:00'),
(23, 4, '2024-06-03 12:00:00', '2024-06-07 12:00:00'),
(5, 3, '2024-06-04 13:00:00', '2024-06-08 13:00:00'),
(18, 2, '2024-06-05 14:00:00', '2024-06-09 14:00:00'),
(7, 4, '2024-06-06 15:00:00', '2024-06-10 15:00:00'),
(30, 1, '2024-06-07 16:00:00', '2024-06-11 16:00:00'),
(15, 3, '2024-06-08 17:00:00', '2024-06-12 17:00:00'),
(20, 1, '2024-06-09 18:00:00', '2024-06-13 18:00:00'),
(9, 2, '2024-06-10 19:00:00', '2024-06-14 19:00:00');