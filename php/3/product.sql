CREATE TABLE products (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	type TEXT,
	firstname TEXT,
	mainname TEXT,
	title  TEXT,
	price float,
	numpages int,
	playlength int,
	discount int
);

INSERT INTO products (id, type, firstname, mainname, title, price, numpages, playlength, discount) VALUES (1, 'cd', 'First Nsme', 'Last Name', 'Test Ttile', 400, 0, 20, 0);
