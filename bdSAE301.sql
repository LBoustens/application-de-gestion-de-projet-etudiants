CREATE TABLE contexte(
   Id_contexte INT,
   semestre VARCHAR(20) NOT NULL,
   intitule VARCHAR(50) NOT NULL,
   annneecrea INT NOT NULL,
   PRIMARY KEY(Id_contexte)
);

CREATE TABLE categorie(
   Id_categorie INT,
   nom_cate VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_categorie)
);

CREATE TABLE utilisateur(
   Id_utilisateur INT,
   nom VARCHAR(50) NOT NULL,
   identifiantiut VARCHAR(50) NOT NULL,
   email VARCHAR(50) NOT NULL,
   mdp VARCHAR(50) NOT NULL,
   photodeprofil VARCHAR(128),
   statut BOOLEAN NOT NULL,
   prenom VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_utilisateur)
);

CREATE TABLE tags(
   Id_tags INT,
   nom_tag VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_tags)
);

CREATE TABLE projet(
   Id_projet INT,
   titre VARCHAR(50) NOT NULL,
   description VARCHAR(250) NOT NULL,
   image VARCHAR(128),
   liendemo VARCHAR(50) NOT NULL,
   Id_contexte INT NOT NULL,
   PRIMARY KEY(Id_projet),
   FOREIGN KEY(Id_contexte) REFERENCES contexte(Id_contexte)
);

CREATE TABLE sources(
   Id_projet INT,
   Id_sources INT,
   url VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_projet, Id_sources),
   FOREIGN KEY(Id_projet) REFERENCES projet(Id_projet)
);

CREATE TABLE commentaire(
   Id_commentaire INT,
   message VARCHAR(250) NOT NULL,
   date_publication_mess DATE NOT NULL,
   Id_projet INT NOT NULL,
   Id_utilisateur INT NOT NULL,
   PRIMARY KEY(Id_commentaire),
   FOREIGN KEY(Id_projet) REFERENCES projet(Id_projet),
   FOREIGN KEY(Id_utilisateur) REFERENCES utilisateur(Id_utilisateur)
);

CREATE TABLE notation(
   Id_note INT,
   note INT NOT NULL,
   date_publication_note DATE NOT NULL,
   Id_utilisateur INT NOT NULL,
   Id_projet INT NOT NULL,
   PRIMARY KEY(Id_note),
   FOREIGN KEY(Id_utilisateur) REFERENCES utilisateur(Id_utilisateur),
   FOREIGN KEY(Id_projet) REFERENCES projet(Id_projet)
);

CREATE TABLE appartient(
   Id_projet INT,
   Id_categorie INT,
   PRIMARY KEY(Id_projet, Id_categorie),
   FOREIGN KEY(Id_projet) REFERENCES projet(Id_projet),
   FOREIGN KEY(Id_categorie) REFERENCES categorie(Id_categorie)
);

CREATE TABLE participer(
   Id_projet INT,
   Id_utilisateur INT,
   PRIMARY KEY(Id_projet, Id_utilisateur),
   FOREIGN KEY(Id_projet) REFERENCES projet(Id_projet),
   FOREIGN KEY(Id_utilisateur) REFERENCES utilisateur(Id_utilisateur)
);

CREATE TABLE associer(
   Id_projet INT,
   Id_tags INT,
   PRIMARY KEY(Id_projet, Id_tags),
   FOREIGN KEY(Id_projet) REFERENCES projet(Id_projet),
   FOREIGN KEY(Id_tags) REFERENCES tags(Id_tags)
);
