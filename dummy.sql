insert into vak(naam) values("Engels");
insert into vak(naam) values("Nederlands");
insert into vak(naam) values("Rekenen");
insert into vak(naam) values("Beroepsgericht");

insert into klas(naam) values("A");
insert into klas(naam) values("B");
insert into klas(naam) values("C");

insert into user(naam,email,wachtwoord,rol,idklas) values("Billy","128@gmail.com","$2y$10$jFEfocqNzNvTPHJadGZZ7eZW1MOLc8L3gtwlJMW8Yb.9sfCAX0zz.",0,"2");
insert into user(naam,email,wachtwoord,rol,idklas) values("Joey","124@gmail.com","$2y$10$jFEfocqNzNvTPHJadGZZ7eZW1MOLc8L3gtwlJMW8Yb.9sfCAX0zz.",1,"1");
insert into user(naam,email,wachtwoord,rol,idklas) values("Omar","125@gmail.com","$2y$10$jFEfocqNzNvTPHJadGZZ7eZW1MOLc8L3gtwlJMW8Yb.9sfCAX0zz.",1,"2");
insert into user(naam,email,wachtwoord,rol,idklas) values("Lee-Ann","126@gmail.com","$2y$10$jFEfocqNzNvTPHJadGZZ7eZW1MOLc8L3gtwlJMW8Yb.9sfCAX0zz.",1,"3");
insert into user(naam,email,wachtwoord,rol,idklas) values("Isabella","127@gmail.com","$2y$10$jFEfocqNzNvTPHJadGZZ7eZW1MOLc8L3gtwlJMW8Yb.9sfCAX0zz.",1,"1");

insert into Taak(bereikt, doel, drempel,iduser,idvak) values("2e geworden","1e worden","1e verslaan",4,4);
insert into Taak(bereikt, doel, drempel,iduser,idvak) values("2e geworden","1e worden","1e verslaan",5,4);
insert into Taak(bereikt, doel, drempel,iduser,idvak) values("2e geworden","1e worden","1e verslaan",4,4);

