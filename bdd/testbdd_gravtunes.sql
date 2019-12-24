

-------- CONTRAINTES CLEF ETRANGERES ---------


syntaxe : FK_nomtable_nomattribut

---- TABLE VINYLE -----

-- CONSTRAINT FK_vinyle_iduser foreign key (idUser) REFERENCES User(idUser) ;

-- CONSTRAINT FK_vinyle_idplaylist foreign key (idPlaylist) REFERENCES Playlist(idPlaylist) ;

---- TABLE COLLAB -----

-- CONSTRAINT FK_collab_iduser foreign key (idUser) REFERENCES User(idUser) ;

-- CONSTRAINT FK_collab_idvinyle foreign key (idVinyle) REFERENCES Vinyle(idVinyle) ;

---- TABLE COMMANDE -----

-- CONSTRAINT FK_commande_iduser foreign key (idUser) REFERENCES User(idUser) ;

-- CONSTRAINT FK_commande_idvinyle foreign key (idVinyle) REFERENCES Vinyle(idVinyle) ;

---- TABLE FICHIER -----

-- CONSTRAINT FK_fichier_iduser foreign key (idUser) REFERENCES User(idUser) ;

---- TABLE PLAYLIST -----

-- CONSTRAINT FK_playlist_iduser foreign key (idUser) REFERENCES User(idUser) ;

---- TABLE TICKET -----

-- CONSTRAINT FK_ticket_iduser foreign key (idUser) REFERENCES User(idUser) ;

---- TABLE POSSEDEFP -----

-- CONSTRAINT FK_possedefp_idfichier foreign key (idFichier) REFERENCES Fichier(idFichier);

-- CONSTRAINT FK_possedefp_idplaylist foreign key (idPlaylist) REFERENCES Playlist(idPlaylist);


	------------------
	------------------
	TEST BDD GRAVTUNES
	------------------
	------------------


------ TEST 1 ------

select nomPlaylist, fichier.link 
from fichier natural join possedefp natural join playlist ;


------ TEST 2 ------

select sum(prix) 
from vinyle 
where iduser = 1 ;

------ TEST 3 ------

select fichier.nomFichier
from fichier natural join possedefp
where possedefp.idPlaylist = 1 ;

------ TEST 4 ------

select distinct fichier.nomFichier
from fichier natural join possedefp natural join playlist
where playlist.idPlaylist = 2 ;

