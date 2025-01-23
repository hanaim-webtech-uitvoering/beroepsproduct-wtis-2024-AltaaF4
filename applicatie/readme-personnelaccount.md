voor het aanmaken van een personeel account moet je eerst het wachtwoord hash maken

https://onlinephp.io/password-hash

daarna gebruik je dit commando

INSERT INTO [User] (username, password, first_name, last_name, role, address) 
VALUES ('personeel123', 'WW HASH HIER', 'John', 'Doe', 'Personnel', 'Adresstraat 123');

done