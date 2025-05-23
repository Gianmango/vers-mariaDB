DROP DATABASE IF EXISTS testdb;
CREATE DATABASE testdb;
USE testdb;

DROP TABLE IF EXISTS utenti;
CREATE TABLE utenti (
    uID INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ruolo VARCHAR(50) NOT NULL
);

DROP TABLE IF EXISTS prodotti;
CREATE TABLE prodotti (
    pID INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    prezzo DECIMAL(10,2) NOT NULL,
    quantita INT DEFAULT 1
);

DROP TABLE IF EXISTS Magazzino;
CREATE TABLE Magazzino (
    mID INT AUTO_INCREMENT PRIMARY KEY,
    posizione VARCHAR(255) NOT NULL
);

<<<<<<< HEAD
DROP TABLE IF EXISTS MagazzinoProdotti;
CREATE TABLE MagazzinoProdotti (
    magazzinoID INT,
    prodottoID INT,
    quantita INT NOT NULL DEFAULT 0,
    PRIMARY KEY (magazzinoID, prodottoID),
    FOREIGN KEY (magazzinoID) REFERENCES Magazzino(mID),
    FOREIGN KEY (prodottoID) REFERENCES prodotti(pID)
);



-- Inserimento di utenti
INSERT INTO utenti (username, password, ruolo) VALUES
('admin', 'adminpass', 'amministratore'),
('utente1', 'utente1pass', 'utente'),
('utente2', 'utente2pass', 'utente');

-- Inserimento di magazzini
INSERT INTO Magazzino (posizione) VALUES
('Roma'),
('Milano'),
('Napoli'),
('Torino'),
('Firenze');

-- Inserimento di prodotti (con nome, prezzo, quantità totale disponibile)
INSERT INTO prodotti (nome, prezzo, quantita) VALUES
('Laptop', 799.99, 50),
('Smartphone', 499.50, 80),
('Stampante', 159.90, 30),
('Monitor', 219.99, 40),
('Tastiera', 89.00, 60);

-- Inserimento di disponibilità dei prodotti nei magazzini
-- Tabella: disponibilita(magazzinoID, prodottoID, quantita)
INSERT INTO MagazzinoProdotti(magazzinoID, prodottoID, quantita) VALUES
(1, 1, 10), -- Roma - Laptop
(1, 2, 15), -- Roma - Smartphone
(2, 1, 5),  -- Milano - Laptop
(2, 3, 7),  -- Milano - Stampante
(3, 4, 12), -- Napoli - Monitor
(3, 5, 20), -- Napoli - Tastiera
(4, 2, 8),  -- Torino - Smartphone
(4, 3, 6),  -- Torino - Stampante
(5, 1, 4),  -- Firenze - Laptop
(5, 5, 10); -- Firenze - Tastiera
