DROP TABLE agency;

CREATE TABLE `agency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branch` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phoneNumber` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `town` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abreviation` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registrationNumber` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `poBox` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO agency VALUES("1","BALATCHI COOPERATIVE CREDIT UNION LIMITED","1254ZED","FORMAL YDE PARK  NKWEN","NKWEN BRANCH","677 62 27 78 / 676 73 79 49 / 677 51 63 00","BAMENDA","BALACCUL","REGISTRATION N° NW/CO/036/14/15686","303 BAMENDA");



DROP TABLE beneficiary;

CREATE TABLE `beneficiary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_member` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `relation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ratio` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7ABF446A56D34F95` (`id_member`),
  CONSTRAINT `FK_7ABF446A56D34F95` FOREIGN KEY (`id_member`) REFERENCES `member` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO beneficiary VALUES("1","1","","","0");
INSERT INTO beneficiary VALUES("2","2","","","0");
INSERT INTO beneficiary VALUES("3","3","","","0");
INSERT INTO beneficiary VALUES("4","4","","","0");
INSERT INTO beneficiary VALUES("5","5","","","0");
INSERT INTO beneficiary VALUES("6","6","","","0");
INSERT INTO beneficiary VALUES("7","7","","","0");
INSERT INTO beneficiary VALUES("8","8","","","0");
INSERT INTO beneficiary VALUES("9","9","","","0");
INSERT INTO beneficiary VALUES("10","10","","","0");
INSERT INTO beneficiary VALUES("11","11","","","0");
INSERT INTO beneficiary VALUES("12","12","","","0");
INSERT INTO beneficiary VALUES("13","13","","","0");
INSERT INTO beneficiary VALUES("14","14","","","0");
INSERT INTO beneficiary VALUES("15","15","","","0");
INSERT INTO beneficiary VALUES("16","16","MELONG TCHINDA MIRIANE","daugther","50");
INSERT INTO beneficiary VALUES("17","16","FOUDJI FRANKLIN","son","50");
INSERT INTO beneficiary VALUES("18","17","NZOYEM JOEL","Son","40");
INSERT INTO beneficiary VALUES("19","17","KUETE ACHILLE","Son","30");
INSERT INTO beneficiary VALUES("20","17","DOUANLA LISETTE","Daugther","30");
INSERT INTO beneficiary VALUES("21","18","NGODA ANGELINE","WIFE","0");
INSERT INTO beneficiary VALUES("22","19","TSAYEM GODWILL","SON","100");
INSERT INTO beneficiary VALUES("23","20","","","0");
INSERT INTO beneficiary VALUES("24","21","","","0");
INSERT INTO beneficiary VALUES("25","22","","","0");
INSERT INTO beneficiary VALUES("26","23","NGOUNE MARCELINE","WIFE","40");
INSERT INTO beneficiary VALUES("27","23","AZANGUE NONKI THIERRY","Son","30");
INSERT INTO beneficiary VALUES("28","23","AZANGUE MELONG JUDITH","Daugther","30");
INSERT INTO beneficiary VALUES("29","24","MARY NWE","//","100");
INSERT INTO beneficiary VALUES("30","25","SPERASER NOSTBA T. A. ","Daugther","50");
INSERT INTO beneficiary VALUES("31","25","NGAM FOFO EUNICE","WIFE","50");
INSERT INTO beneficiary VALUES("32","26","DJOUSSE N. DORIS","WIFE","100");
INSERT INTO beneficiary VALUES("33","27","","","0");
INSERT INTO beneficiary VALUES("34","28","","","0");
INSERT INTO beneficiary VALUES("35","29","","","0");
INSERT INTO beneficiary VALUES("36","30","","","0");
INSERT INTO beneficiary VALUES("37","31","TANKFU SOLANGE","sister","50");
INSERT INTO beneficiary VALUES("38","31","TANKFU MARIE","Mother","50");
INSERT INTO beneficiary VALUES("39","32","BRIGET SANGAAH","Mother","100");
INSERT INTO beneficiary VALUES("40","33","DOUANLA LAMBOU MIREILLE","WIFE","50");
INSERT INTO beneficiary VALUES("41","33","LAMBOU KUETE DARIOS","SON","50");
INSERT INTO beneficiary VALUES("42","34","","","0");
INSERT INTO beneficiary VALUES("43","35","S. JUDE NDEZDENYUY","SON","100");
INSERT INTO beneficiary VALUES("44","36","","","0");
INSERT INTO beneficiary VALUES("45","37","ZANZI KEVINE","WIFE","100");
INSERT INTO beneficiary VALUES("46","38","","","0");
INSERT INTO beneficiary VALUES("47","39","LEPAZA GONMAZOU GERMAINE","WIFE","60");
INSERT INTO beneficiary VALUES("48","39","KENNE NGOULA VANESSA DAREL","Daugther","10");
INSERT INTO beneficiary VALUES("49","39","TAPA NGOULA ANELKA DAREL","Son","10");
INSERT INTO beneficiary VALUES("50","39","KENFACK NGOULA JORDAN","Son","10");
INSERT INTO beneficiary VALUES("51","39","MOGOU NGOULA JORDIX","Son","10");
INSERT INTO beneficiary VALUES("52","40","ABDU AKIMUH","Brother","100");
INSERT INTO beneficiary VALUES("53","41","VUSUFABOM LOUIS","SON","100");
INSERT INTO beneficiary VALUES("54","42","TIAYO SAHA WILBROD","SON","100");
INSERT INTO beneficiary VALUES("55","43","","","0");
INSERT INTO beneficiary VALUES("56","44","","","0");
INSERT INTO beneficiary VALUES("57","45","KENNE ELISE","WIFE","100");
INSERT INTO beneficiary VALUES("58","46","QUEENTOLINE BELIVEN BONGSHU","SISTER","100");
INSERT INTO beneficiary VALUES("59","47","ALANGE MACE-NOEL N.","SON","85");
INSERT INTO beneficiary VALUES("60","47","SHELOH LUCY","MOTHER","15");
INSERT INTO beneficiary VALUES("61","48","","","0");
INSERT INTO beneficiary VALUES("62","49","FANKA BIH MIRABELLE","Sister","60");
INSERT INTO beneficiary VALUES("63","49","Doh John Paul Mbumah","Brother","40");
INSERT INTO beneficiary VALUES("64","50","","","0");
INSERT INTO beneficiary VALUES("65","51","","","0");
INSERT INTO beneficiary VALUES("66","52","DZOUOGOUO SANDRINE","WIFE","100");
INSERT INTO beneficiary VALUES("67","53","NTOMBONG FABRISE","BROTHER","100");
INSERT INTO beneficiary VALUES("68","54","DOUANLA HENRIETTE","WIFE","100");
INSERT INTO beneficiary VALUES("69","55","DZOUOGOUO SANDRINE","Mother","100");
INSERT INTO beneficiary VALUES("70","56","","","0");
INSERT INTO beneficiary VALUES("71","57","","","0");
INSERT INTO beneficiary VALUES("72","58","DIMLA OLIVIA BUNUDI","WIFE","25");
INSERT INTO beneficiary VALUES("73","58","YUNGSI NICOLAS","SON","25");
INSERT INTO beneficiary VALUES("74","58","YUNGSI ALECZANDRA","SON","25");
INSERT INTO beneficiary VALUES("75","58","YUNGSI GISLEN COURAGE","SON","25");
INSERT INTO beneficiary VALUES("76","59","FOUADJEU ARISTIDE","WIFE","100");
INSERT INTO beneficiary VALUES("77","60","NOUKEGI W. SUZANNE","Daugther","100");
INSERT INTO beneficiary VALUES("78","61","NDAH BORIS","SON","25");
INSERT INTO beneficiary VALUES("79","61","ANGUH KEIN","SON","25");
INSERT INTO beneficiary VALUES("80","61","MAKAH JOCETTE","Daugther","25");
INSERT INTO beneficiary VALUES("81","61","Njob John","Husband","25");
INSERT INTO beneficiary VALUES("82","62","MUKONCHE RANDY NOEL","SON","100");
INSERT INTO beneficiary VALUES("83","63","","","0");
INSERT INTO beneficiary VALUES("84","64","TEDONGMOUO DJOUSSE V.","Soeur","30");
INSERT INTO beneficiary VALUES("85","64","NGOULA LARIO","FILS","70");
INSERT INTO beneficiary VALUES("86","65","ANETTE NSAM AJUME","WIFE","100");
INSERT INTO beneficiary VALUES("87","66","NZIENO SOPSI ADELAIDE","Daugther","50");
INSERT INTO beneficiary VALUES("88","66","FOTSINGUE SOPSI CYRANO","SON","50");
INSERT INTO beneficiary VALUES("89","67","TEDONGMO ROSTAND ","friend","100");
INSERT INTO beneficiary VALUES("90","68","NJIOGFUO CARINE","SISTER","20");
INSERT INTO beneficiary VALUES("91","68","NJIOGFUO EMILIA","SISTER","30");
INSERT INTO beneficiary VALUES("92","68","NJIOGFUO CHRISTIAN","BROTHER","50");
INSERT INTO beneficiary VALUES("93","69","","","0");
INSERT INTO beneficiary VALUES("94","70","WONOU FOKOU BRANDON","SON","75");
INSERT INTO beneficiary VALUES("95","70","DJUIPAT NADEGE","WIFE","25");
INSERT INTO beneficiary VALUES("96","71","","","0");
INSERT INTO beneficiary VALUES("97","72","TCHOULA FOSSAP SOPHIE","WIFE","50");
INSERT INTO beneficiary VALUES("98","72","HOUYEM DOUANLA RANDY","SON","50");
INSERT INTO beneficiary VALUES("99","73","NGANFOUO NADEGE","WIFE","75");
INSERT INTO beneficiary VALUES("100","73","BAWJEKO ABDIAS","SON","25");
INSERT INTO beneficiary VALUES("101","74","","","0");
INSERT INTO beneficiary VALUES("102","75","","","0");
INSERT INTO beneficiary VALUES("103","76","NGINGAYE BEATRICE","WIFE","50");
INSERT INTO beneficiary VALUES("104","76","TCHOUALA KENVO RAMICESSE","DAUGTHER","50");
INSERT INTO beneficiary VALUES("105","77","NGASSI DONFACK CAIELLE","WIFE","50");
INSERT INTO beneficiary VALUES("106","77","MENDONFOUE T. KANA ALIDA","SISTER","50");
INSERT INTO beneficiary VALUES("107","78","","","0");
INSERT INTO beneficiary VALUES("108","79","MAFOUO CARINE TANGMO","Daugther","100");
INSERT INTO beneficiary VALUES("109","80","TSAYEM BORIS","BROTHER","50");
INSERT INTO beneficiary VALUES("110","80","TEPONNO","BROTHER","50");
INSERT INTO beneficiary VALUES("111","81","//","//","0");
INSERT INTO beneficiary VALUES("112","82","//","//","0");
INSERT INTO beneficiary VALUES("113","83","GLADYS AKUNGHA","MOTHER","100");
INSERT INTO beneficiary VALUES("114","84","NFONE BIH STELLA","WIFE","100");
INSERT INTO beneficiary VALUES("115","85","AWAH BLESSING","//","100");
INSERT INTO beneficiary VALUES("116","86","ACLA ROSINE","WIFE","100");
INSERT INTO beneficiary VALUES("117","87","SAMPIE JEAN EBENEZER","HUSBAND","50");
INSERT INTO beneficiary VALUES("118","87","SAMPIE FOYANG DURONE E.","SON","25");
INSERT INTO beneficiary VALUES("119","87","SAMPIE MEUKAM GABRIEL","SON","25");
INSERT INTO beneficiary VALUES("120","88","TAKU JOEL","SON","60");
INSERT INTO beneficiary VALUES("121","88","TAKU HONORINE","WIFE","40");
INSERT INTO beneficiary VALUES("122","89","NCHINDA ERIC","BROTHER","100");
INSERT INTO beneficiary VALUES("123","90","YUNIWO MUSTERPHY ASUNGUI","HUSBAND","100");
INSERT INTO beneficiary VALUES("124","91","//","//","0");
INSERT INTO beneficiary VALUES("125","92","LONKENG EDWIGE","//","100");
INSERT INTO beneficiary VALUES("126","93","AFIS NKEJUH","SON","100");
INSERT INTO beneficiary VALUES("127","94","DOUANLA ULEKE","","50");
INSERT INTO beneficiary VALUES("128","94","MELI CARINE","DAUGTHER","50");
INSERT INTO beneficiary VALUES("129","95","MAGOH MARIE","MOTHER","100");
INSERT INTO beneficiary VALUES("130","96","LABAN IVO","DAUGTHER","100");
INSERT INTO beneficiary VALUES("131","97","//","//","0");
INSERT INTO beneficiary VALUES("132","98","TATANG MARKAA","WIFE","100");
INSERT INTO beneficiary VALUES("133","99","SIVLA PETER","HUSBAND","50");
INSERT INTO beneficiary VALUES("134","99","SIVLA TYNA","Daugther","50");
INSERT INTO beneficiary VALUES("135","100","NCHE -NJI JUDE NGWA","SON","100");
INSERT INTO beneficiary VALUES("136","101","//","//","0");
INSERT INTO beneficiary VALUES("137","102","//","//","0");
INSERT INTO beneficiary VALUES("138","103","//","//","0");
INSERT INTO beneficiary VALUES("139","104","FOSSOP N LIVIN","WIFE","25");
INSERT INTO beneficiary VALUES("140","104","APONO TSAGAOU FAVOUR","DAUGTHER","25");
INSERT INTO beneficiary VALUES("141","104","FOSSO TSAGOUO NATAN","SON","25");
INSERT INTO beneficiary VALUES("142","104","MAFFO F SAYOUO T","SON","25");
INSERT INTO beneficiary VALUES("143","105","//","//","0");
INSERT INTO beneficiary VALUES("144","106","EMMERICA MAYI","WIFE","100");
INSERT INTO beneficiary VALUES("145","107","//","//","0");
INSERT INTO beneficiary VALUES("146","108","//","//","0");
INSERT INTO beneficiary VALUES("147","109","CHE METCHOUYERE","SON","50");
INSERT INTO beneficiary VALUES("148","109","FOPA HERMAN","BOTHER","50");
INSERT INTO beneficiary VALUES("149","110","APONNO DANIELLA","DAUGTHER","25");
INSERT INTO beneficiary VALUES("150","110","METCHOUYERE TEPONNO","HUSBAND","50");
INSERT INTO beneficiary VALUES("151","110","CHE METCHOUYERE","SON","25");
INSERT INTO beneficiary VALUES("152","111","YOTA FOKOU ELVIRA","DAUGTHER","100");
INSERT INTO beneficiary VALUES("153","112","//","//","0");
INSERT INTO beneficiary VALUES("154","113","//","//","0");
INSERT INTO beneficiary VALUES("155","114","FOFE MICLANCHE NZOYEM","WIFE","25");
INSERT INTO beneficiary VALUES("156","114","NZOYEM KUEFFOUO MAKSWELL","SON","50");
INSERT INTO beneficiary VALUES("157","114","TIAYO KUEFFOUO ANAILLE","DAUGTHER","25");
INSERT INTO beneficiary VALUES("158","115","//","//","0");
INSERT INTO beneficiary VALUES("159","116","//","//","0");
INSERT INTO beneficiary VALUES("160","117","//","//","0");
INSERT INTO beneficiary VALUES("161","118","//","//","0");
INSERT INTO beneficiary VALUES("162","119","//","//","0");
INSERT INTO beneficiary VALUES("163","120","//","//","0");
INSERT INTO beneficiary VALUES("164","121","//","//","0");
INSERT INTO beneficiary VALUES("165","122","//","//","0");
INSERT INTO beneficiary VALUES("167","124","aeee","dsds","0");
INSERT INTO beneficiary VALUES("168","127","sqdsq","sds","100");
INSERT INTO beneficiary VALUES("169","128","dsds","fdf","100");
INSERT INTO beneficiary VALUES("170","129","dss","dsfds","45");



DROP TABLE classe;

CREATE TABLE `classe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO classe VALUES("1","Class 1");
INSERT INTO classe VALUES("2","Class 2");
INSERT INTO classe VALUES("3","Class 3");
INSERT INTO classe VALUES("4","Class 4");
INSERT INTO classe VALUES("5","Class 5");
INSERT INTO classe VALUES("6","Class 6");
INSERT INTO classe VALUES("7","Class 7");



DROP TABLE client;

CREATE TABLE `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_collector` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `idNumber` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `balance` bigint(20) NOT NULL,
  `balanceBF` bigint(20) NOT NULL,
  `withdrawal1` bigint(20) NOT NULL,
  `withdrawal2` bigint(20) NOT NULL,
  `charges` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C74404555E237E06` (`name`),
  UNIQUE KEY `UNIQ_C7440455F581B7CE` (`idNumber`),
  KEY `IDX_C74404554EEE496B` (`id_collector`),
  CONSTRAINT `FK_C74404554EEE496B` FOREIGN KEY (`id_collector`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO client VALUES("1","6","MACEL SAYOH","","200","0","0","0","0");
INSERT INTO client VALUES("2","6","MARCELINE ANDRUSAL","","3000","0","0","0","0");
INSERT INTO client VALUES("3","6","KWAKEP PELAGIE","","5000","0","0","0","0");
INSERT INTO client VALUES("4","6","TCHINDA ALEX CARNOT","","10000","0","0","0","0");
INSERT INTO client VALUES("5","6","JOHN-MARY FANYUY","","1000","0","1500","0","0");
INSERT INTO client VALUES("6","6","ROMARIO","","0","0","0","0","0");
INSERT INTO client VALUES("7","6","TCHI NIMPA ROMAIN","","0","0","0","0","0");
INSERT INTO client VALUES("8","6","TIYA MARTINE","","0","0","0","0","0");
INSERT INTO client VALUES("9","6","MOKO CELESTIN","","0","0","0","0","0");
INSERT INTO client VALUES("10","6","VALENTINA SYVLA","","0","0","0","0","0");
INSERT INTO client VALUES("11","6","DONGMO J. JUDITHE","","0","0","0","0","0");
INSERT INTO client VALUES("12","6","TALLA CEDRIC","","0","0","0","0","0");
INSERT INTO client VALUES("13","6","AWOUMFAK CARINE","","0","0","0","0","0");
INSERT INTO client VALUES("14","6","YEMELONG SIMON","","0","0","0","0","0");
INSERT INTO client VALUES("15","6","TOUOBOU SERGE","","0","0","0","0","0");
INSERT INTO client VALUES("16","6","ODETTE","","0","0","0","0","0");
INSERT INTO client VALUES("17","6","FOYET GESCO MARTIAL","","0","0","0","0","0");
INSERT INTO client VALUES("18","6","BONGSIYI AJARA","","0","0","0","0","0");
INSERT INTO client VALUES("19","6","TIMENI KINSLEY","","0","0","0","0","0");
INSERT INTO client VALUES("20","6","TCHOUALA  THEOPHIL / DOUANLA","","0","0","0","0","0");
INSERT INTO client VALUES("21","6","SONKET KENNE OLIVIER","","0","0","0","0","0");
INSERT INTO client VALUES("22","6","GILBERT TAMFU","","0","0","0","0","0");
INSERT INTO client VALUES("23","6","FEUNDOUNG TATEMKAP LAWRENCE","","0","0","0","0","0");
INSERT INTO client VALUES("24","6","NGEN EMMA M.","","0","0","0","0","0");
INSERT INTO client VALUES("25","6","CHI EVELINE","","0","0","0","0","0");
INSERT INTO client VALUES("26","6","DOUANLA FLORENCE","","0","0","0","0","0");
INSERT INTO client VALUES("27","6","NJOLAI MARIE CLAIRE","","0","0","0","0","0");
INSERT INTO client VALUES("28","6","CLITON","","0","0","0","0","0");
INSERT INTO client VALUES("29","6","NGOUMGANG GILBERT","","0","0","0","0","0");
INSERT INTO client VALUES("30","6","LABAN TATA","","0","0","0","0","0");
INSERT INTO client VALUES("31","6","TEDONGMO ROSTAND","","0","0","0","0","0");
INSERT INTO client VALUES("32","6","TIWA","","0","0","0","0","0");
INSERT INTO client VALUES("33","6","TANEKING HERVE YANNICK","","0","0","0","0","0");
INSERT INTO client VALUES("34","6","ELIBE","","0","0","0","0","0");
INSERT INTO client VALUES("35","6","ASAA TIDO BERTO","","0","0","0","0","0");
INSERT INTO client VALUES("36","6","AKULA EZELKI","","0","0","0","0","0");
INSERT INTO client VALUES("37","6","BOUZEKO SAHA","","0","0","0","0","0");
INSERT INTO client VALUES("38","6","NGOUEKA MARIE CLAIRE","","0","0","0","0","0");
INSERT INTO client VALUES("39","6","SIVLA PETER","","0","0","0","0","0");
INSERT INTO client VALUES("40","6","ZEMTSOP CHRISTELLE VANESSA","","0","0","0","0","0");
INSERT INTO client VALUES("41","6","WAMENE YONTA STEPHEN","","0","0","0","0","0");
INSERT INTO client VALUES("42","6","ABDULIA ADAMU","","0","0","0","0","0");
INSERT INTO client VALUES("43","6","FOUABANG YANNICK BRUNO","","0","0","0","0","0");
INSERT INTO client VALUES("44","6","YONTA SEGNING IGOR","","0","0","0","0","0");
INSERT INTO client VALUES("45","6","JULIE TOBEN","","0","0","0","0","0");
INSERT INTO client VALUES("46","6","HONGA ROMARIC","","0","0","0","0","0");
INSERT INTO client VALUES("47","6","ADJIFFO FOFIE BRICE","","0","0","0","0","0");
INSERT INTO client VALUES("48","6","MONYUYTAA PETER NSOSEKA","","0","0","0","0","0");
INSERT INTO client VALUES("49","6","MELI SONNA OSCAR","","0","0","0","0","0");
INSERT INTO client VALUES("50","6","KOWO SOTSE ETIENNE","","0","0","0","0","0");
INSERT INTO client VALUES("51","5","SIMO HERVE","","0","6500","0","0","0");
INSERT INTO client VALUES("52","5","MAFO DIVIANE","","0","-800","0","0","0");
INSERT INTO client VALUES("53","5","SONWA CARINE","","0","1000","0","0","0");
INSERT INTO client VALUES("54","5","BRAUMDY JACOB","","0","0","0","0","0");
INSERT INTO client VALUES("55","5","BELINDA ECHUNJEL","","0","0","0","0","0");
INSERT INTO client VALUES("56","5","BUH EVERT EKEI","","0","0","0","0","0");
INSERT INTO client VALUES("57","5","IYEFOU VIVIANE","","0","0","0","0","0");
INSERT INTO client VALUES("58","5","LONGOU","","0","0","0","0","0");
INSERT INTO client VALUES("59","5","GILBERT","","0","0","0","0","0");
INSERT INTO client VALUES("60","5","FOUFIE IDRISSE","","0","0","0","0","0");
INSERT INTO client VALUES("61","5","NGUM YVETTE","","0","0","0","0","0");
INSERT INTO client VALUES("62","5","TSONWA NICODERM","","0","0","0","0","0");
INSERT INTO client VALUES("63","5","TEMMY CRYSANTUS","","0","0","0","0","0");
INSERT INTO client VALUES("64","5","MELI","","0","0","0","0","0");
INSERT INTO client VALUES("65","5","TIYO MEBRICE","","0","0","0","0","0");
INSERT INTO client VALUES("66","5","KIMBI ESTHER","","0","0","0","0","0");
INSERT INTO client VALUES("67","5","NGWANY GRACE","","0","0","0","0","0");
INSERT INTO client VALUES("68","5","MANKAFON NICOLINE","","0","0","0","0","0");
INSERT INTO client VALUES("69","5","DZOYEM PATRICE","","0","0","0","0","0");
INSERT INTO client VALUES("70","5","YEMELONG YOMTA","","0","0","0","0","0");
INSERT INTO client VALUES("71","5","LACMENE","","0","0","0","0","0");
INSERT INTO client VALUES("72","5","KIATSOP JOSEP","","0","0","0","0","0");
INSERT INTO client VALUES("73","5","ASWEBOM LAWENCE","","0","0","0","0","0");
INSERT INTO client VALUES("74","5","SAHA TIAYO GERSINO","","0","0","0","0","0");
INSERT INTO client VALUES("75","5","SURPLUS","","0","0","0","0","0");
INSERT INTO client VALUES("76","5","NAME NARCISE","","0","0","0","0","0");
INSERT INTO client VALUES("77","5","SONKOUE NADEGE","","0","0","0","0","0");
INSERT INTO client VALUES("78","5","TIOMELA JEAN-CLAUDE","","0","0","0","0","0");
INSERT INTO client VALUES("79","5","TIOBOU SEVERINE","","0","0","0","0","0");
INSERT INTO client VALUES("80","5","LEDJIOYA ANICELINE","","0","0","0","0","0");
INSERT INTO client VALUES("81","5","KENNE MERLIN","","0","0","0","0","0");
INSERT INTO client VALUES("82","5","FOKOU WONOU ERIC","","0","0","0","0","0");
INSERT INTO client VALUES("83","5","DOUANLA DORIANE","","0","0","0","0","0");
INSERT INTO client VALUES("84","5","TATIA ERNEST","","0","0","0","0","0");
INSERT INTO client VALUES("85","5","RAOUL","","0","0","0","0","0");
INSERT INTO client VALUES("86","5","KENNE TIA","","0","0","0","0","0");
INSERT INTO client VALUES("87","5","DOUNKENG SAMMUEL","","0","0","0","0","0");
INSERT INTO client VALUES("88","5","TSOUKOUE RODRIGUE","","0","0","0","0","0");
INSERT INTO client VALUES("89","5","TCHOFFO LAZAR","","0","0","0","0","0");
INSERT INTO client VALUES("90","5","LANDO HERVE","","0","0","0","0","0");
INSERT INTO client VALUES("91","5","NAME GRACE","","0","0","0","0","0");
INSERT INTO client VALUES("92","5","TCHOUALA KENNE SIDOINE","","0","0","0","0","0");
INSERT INTO client VALUES("93","5","DJOUKOU ODETTE","","0","0","0","0","0");
INSERT INTO client VALUES("94","5","DOUNLA","","0","0","0","0","0");
INSERT INTO client VALUES("95","5","NAMOU","","0","0","0","0","0");
INSERT INTO client VALUES("96","5","TSASSE ARNAUD","","0","0","0","0","0");
INSERT INTO client VALUES("97","5","TCHAKUOTE BLAISE","","0","0","0","0","0");
INSERT INTO client VALUES("98","5","NWEKE EMMANUEL","","0","0","0","0","0");
INSERT INTO client VALUES("99","5","GISELE","","0","0","0","0","0");
INSERT INTO client VALUES("100","5","TAMAJUNG FELICITAS","","0","0","0","0","0");
INSERT INTO client VALUES("101","5","METHOUYERE","","0","0","0","0","0");
INSERT INTO client VALUES("102","5","HARISON","","0","0","0","0","0");
INSERT INTO client VALUES("103","5","TSAGUE GILBERT","","0","0","0","0","0");
INSERT INTO client VALUES("104","5","MUSA SERAPHINE BERI","","0","0","0","0","0");
INSERT INTO client VALUES("105","5","TSAGUO ALANDRI","","0","0","0","0","0");
INSERT INTO client VALUES("106","5","TCHINDA GODLOVE","","0","0","0","0","0");
INSERT INTO client VALUES("107","5","KEMVO BONIFACE","","0","0","0","0","0");
INSERT INTO client VALUES("108","5","DOUANLA EMMANUEL","","0","0","0","0","0");
INSERT INTO client VALUES("109","5","N ROSE","","0","0","0","0","0");
INSERT INTO client VALUES("110","5","KILA HENDRIATA","","0","0","0","0","0");
INSERT INTO client VALUES("111","5","NYANG MBENG FELIX","","0","0","0","0","0");
INSERT INTO client VALUES("112","5","APLPHONES","","0","0","0","0","0");
INSERT INTO client VALUES("113","5","TCHINDA CELINE","","0","0","0","0","0");
INSERT INTO client VALUES("114","5","DESIRE","","0","0","0","0","0");
INSERT INTO client VALUES("115","5","JEAMS","","0","0","0","0","0");
INSERT INTO client VALUES("116","5","TABOUA MBOUKEU","","0","0","0","0","0");
INSERT INTO client VALUES("117","5","PASCAL NSOM","","0","0","0","0","0");
INSERT INTO client VALUES("118","5","FOUSOP","","0","0","0","0","0");
INSERT INTO client VALUES("119","5","MABE RITA CHE","","0","0","0","0","0");
INSERT INTO client VALUES("120","4","CHENG PRINCESS","","0","0","0","0","0");
INSERT INTO client VALUES("121","4","NJOBA CONTANE","","0","0","0","0","0");
INSERT INTO client VALUES("122","4","KOUTIE PAULINE","","0","0","0","0","0");
INSERT INTO client VALUES("123","4","NGINPOUA JOSEPH","","0","0","0","0","0");
INSERT INTO client VALUES("124","4","RODGER","","0","0","0","0","0");
INSERT INTO client VALUES("125","4","TANKFU RENE","","0","0","0","0","0");
INSERT INTO client VALUES("126","4","NGWAFU GRACE","","0","0","0","0","0");
INSERT INTO client VALUES("127","4","NELSON","","0","0","0","0","0");
INSERT INTO client VALUES("128","4","HONORINE","","0","0","0","0","0");
INSERT INTO client VALUES("129","4","ERIC","","0","0","0","0","0");
INSERT INTO client VALUES("130","4","BELMODOU","","0","0","0","0","0");
INSERT INTO client VALUES("131","4","NICOLINE NCHANG","","0","0","0","0","0");
INSERT INTO client VALUES("132","4","FONKOU GILDOIS","","0","0","0","0","0");
INSERT INTO client VALUES("133","4","REMECS","","0","0","0","0","0");
INSERT INTO client VALUES("134","4","WATER MAN","","0","0","0","0","0");
INSERT INTO client VALUES("135","4","JICK BASIL","","0","0","0","0","0");
INSERT INTO client VALUES("136","4","MEWA","","0","0","0","0","0");
INSERT INTO client VALUES("137","4","ADAMON B","","0","0","0","0","0");
INSERT INTO client VALUES("138","4","NKONGLO HELENE","","0","0","0","0","0");
INSERT INTO client VALUES("139","4","DRAKOM NEW ROAD","","0","0","0","0","0");
INSERT INTO client VALUES("140","4","BONAPRISO","","0","0","0","0","0");
INSERT INTO client VALUES("141","4","ELAIS","","0","0","0","0","0");
INSERT INTO client VALUES("144","4","BOUZEKO SAHA 1","","0","0","0","0","0");
INSERT INTO client VALUES("145","4","MARCEL SAYU","","0","0","0","0","0");
INSERT INTO client VALUES("146","4","MELI THOMAS DESMONG OLIVIER NUDI","","0","0","0","0","0");
INSERT INTO client VALUES("147","4","TAPA","","0","0","0","0","0");
INSERT INTO client VALUES("149","4","BONAPRISO 2","","0","0","0","0","0");
INSERT INTO client VALUES("150","4","FONKOU GILDOIS 2","","0","0","0","0","0");
INSERT INTO client VALUES("151","4","EMMA EGG","","0","0","0","0","0");
INSERT INTO client VALUES("152","4","TANKFU ZEOPAES 1","","0","0","0","0","0");
INSERT INTO client VALUES("153","4","DIFF OLIEY","","0","0","0","0","0");
INSERT INTO client VALUES("154","4","TANKFU ZEOPAES 2","","0","0","0","0","0");
INSERT INTO client VALUES("155","4","COLLBUAT","","0","0","0","0","0");
INSERT INTO client VALUES("157","4","ADAMON B 2","","0","0","0","0","0");
INSERT INTO client VALUES("158","4","TOHAIN ROSE","","0","0","0","0","0");
INSERT INTO client VALUES("159","4","MANE DELA EMMANUEL","","0","0","0","0","0");
INSERT INTO client VALUES("160","4","NFORMI","","0","0","0","0","0");
INSERT INTO client VALUES("161","4","PASCL NSOM","","0","0","0","0","0");



DROP TABLE daily_service_operation;

CREATE TABLE `daily_service_operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_client` int(11) DEFAULT NULL,
  `dateOperation` datetime NOT NULL,
  `currentBalance` bigint(20) NOT NULL,
  `fees` bigint(20) NOT NULL,
  `type_operation` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_currentUser` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_493AB3ED7124524C` (`id_currentUser`),
  KEY `IDX_493AB3EDE173B1B8` (`id_client`),
  CONSTRAINT `FK_493AB3ED7124524C` FOREIGN KEY (`id_currentUser`) REFERENCES `utilisateur` (`id`),
  CONSTRAINT `FK_493AB3EDE173B1B8` FOREIGN KEY (`id_client`) REFERENCES `client` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=282 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO daily_service_operation VALUES("1","51","2017-12-03 08:21:56","2500","2500","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("2","52","2017-12-03 08:21:56","2000","2000","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("3","53","2017-12-03 08:21:56","5000","5000","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("4","54","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("5","55","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("6","56","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("7","57","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("8","58","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("9","59","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("10","60","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("11","61","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("12","62","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("13","63","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("14","64","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("15","65","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("16","66","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("17","67","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("18","68","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("19","69","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("20","70","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("21","71","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("22","72","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("23","73","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("24","74","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("25","75","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("26","76","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("27","77","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("28","78","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("29","79","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("30","80","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("31","81","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("32","82","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("33","83","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("34","84","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("35","85","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("36","86","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("37","87","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("38","88","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("39","89","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("40","90","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("41","91","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("42","92","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("43","93","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("44","94","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("45","95","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("46","96","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("47","97","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("48","98","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("49","99","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("50","100","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("51","101","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("52","102","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("53","103","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("54","104","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("55","105","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("56","106","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("57","107","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("58","108","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("59","109","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("60","110","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("61","111","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("62","112","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("63","113","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("64","114","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("65","115","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("66","116","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("67","117","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("68","118","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("69","119","2017-12-03 08:21:56","0","0","DEPOSIT","5");
INSERT INTO daily_service_operation VALUES("70","51","2017-12-03 08:22:22","2000","500","CHARGES","5");
INSERT INTO daily_service_operation VALUES("71","52","2017-12-03 08:22:22","1200","800","CHARGES","5");
INSERT INTO daily_service_operation VALUES("72","53","2017-12-03 08:22:22","4000","1000","CHARGES","5");
INSERT INTO daily_service_operation VALUES("73","54","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("74","55","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("75","56","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("76","57","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("77","58","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("78","59","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("79","60","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("80","61","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("81","62","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("82","63","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("83","64","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("84","65","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("85","66","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("86","67","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("87","68","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("88","69","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("89","70","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("90","71","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("91","72","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("92","73","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("93","74","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("94","75","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("95","76","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("96","77","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("97","78","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("98","79","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("99","80","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("100","81","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("101","82","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("102","83","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("103","84","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("104","85","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("105","86","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("106","87","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("107","88","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("108","89","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("109","90","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("110","91","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("111","92","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("112","93","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("113","94","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("114","95","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("115","96","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("116","97","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("117","98","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("118","99","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("119","100","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("120","101","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("121","102","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("122","103","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("123","104","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("124","105","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("125","106","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("126","107","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("127","108","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("128","109","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("129","110","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("130","111","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("131","112","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("132","113","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("133","114","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("134","115","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("135","116","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("136","117","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("137","118","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("138","119","2017-12-03 08:22:22","0","0","CHARGES","5");
INSERT INTO daily_service_operation VALUES("139","51","2017-12-03 13:44:53","2500","2000","WITHDRAWAL","5");
INSERT INTO daily_service_operation VALUES("140","52","2017-12-03 13:51:03","-800","2000","WITHDRAWAL","5");
INSERT INTO daily_service_operation VALUES("141","53","2017-12-03 13:53:53","2000","2000","WITHDRAWAL","5");
INSERT INTO daily_service_operation VALUES("142","53","2017-12-03 14:03:01","1000","1000","WITHDRAWAL","5");
INSERT INTO daily_service_operation VALUES("143","120","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("144","121","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("145","122","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("146","123","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("147","124","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("148","125","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("149","126","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("150","127","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("151","128","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("152","129","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("153","130","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("154","131","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("155","132","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("156","133","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("157","134","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("158","135","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("159","136","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("160","137","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("161","138","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("162","139","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("163","140","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("164","141","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("165","144","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("166","145","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("167","146","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("168","147","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("169","149","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("170","150","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("171","151","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("172","152","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("173","153","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("174","154","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("175","155","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("176","157","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("177","158","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("178","159","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("179","160","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("180","161","2017-12-04 09:50:01","0","0","DEPOSIT","4");
INSERT INTO daily_service_operation VALUES("181","1","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("182","2","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("183","3","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("184","4","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("185","5","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("186","6","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("187","7","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("188","8","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("189","9","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("190","10","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("191","11","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("192","12","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("193","13","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("194","14","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("195","15","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("196","16","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("197","17","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("198","18","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("199","19","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("200","20","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("201","21","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("202","22","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("203","23","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("204","24","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("205","25","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("206","26","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("207","27","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("208","28","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("209","29","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("210","30","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("211","31","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("212","32","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("213","33","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("214","34","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("215","35","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("216","36","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("217","37","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("218","38","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("219","39","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("220","40","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("221","41","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("222","42","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("223","43","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("224","44","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("225","45","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("226","46","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("227","47","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("228","48","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("229","49","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("230","50","2017-12-04 09:50:49","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("231","1","2017-12-04 10:03:54","200","200","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("232","2","2017-12-04 10:03:54","3000","3000","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("233","3","2017-12-04 10:03:54","5000","5000","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("234","4","2017-12-04 10:03:54","10000","10000","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("235","5","2017-12-04 10:03:54","2500","2500","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("236","6","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("237","7","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("238","8","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("239","9","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("240","10","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("241","11","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("242","12","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("243","13","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("244","14","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("245","15","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("246","16","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("247","17","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("248","18","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("249","19","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("250","20","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("251","21","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("252","22","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("253","23","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("254","24","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("255","25","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("256","26","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("257","27","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("258","28","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("259","29","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("260","30","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("261","31","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("262","32","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("263","33","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("264","34","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("265","35","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("266","36","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("267","37","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("268","38","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("269","39","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("270","40","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("271","41","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("272","42","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("273","43","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("274","44","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("275","45","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("276","46","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("277","47","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("278","48","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("279","49","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("280","50","2017-12-04 10:03:54","0","0","DEPOSIT","6");
INSERT INTO daily_service_operation VALUES("281","5","2017-12-04 10:04:54","1000","1500","WITHDRAWAL","6");



DROP TABLE groupe;

CREATE TABLE `groupe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO groupe VALUES("1","ADMINISTRATOR");
INSERT INTO groupe VALUES("2","MANAGER");
INSERT INTO groupe VALUES("3","COLLECTOR");
INSERT INTO groupe VALUES("4","CASHER");
INSERT INTO groupe VALUES("5","BOARD");



DROP TABLE internalaccount;

CREATE TABLE `internalaccount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_classe` int(11) DEFAULT NULL,
  `accountNumber` bigint(20) NOT NULL,
  `beginingBalance` decimal(10,0) NOT NULL,
  `accountName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `endingBalance` decimal(10,0) NOT NULL,
  `debit` decimal(10,0) NOT NULL,
  `credit` decimal(10,0) NOT NULL,
  `beginBalanceCode` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'D',
  `endingBalanceCode` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'C',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2B03F0F02D8B2F8F` (`accountNumber`),
  KEY `IDX_2B03F0F0A9B00A7B` (`id_classe`),
  CONSTRAINT `FK_2B03F0F0A9B00A7B` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO internalaccount VALUES("1","1","100000000000","0","MEMBERS SHARES","472000","63000","535000","C","C");
INSERT INTO internalaccount VALUES("2","1","110000000000","0","COMPULSORY RESERVES","0","0","0","C","C");
INSERT INTO internalaccount VALUES("3","1","112000000000","0","GENERAL RESERVES","0","0","0","C","C");
INSERT INTO internalaccount VALUES("4","1","113000000000","0","EDUCATIONAL RESERVES","0","0","0","C","C");
INSERT INTO internalaccount VALUES("5","1","114000000000","0","BUILDING RESERVES","0","0","0","C","C");
INSERT INTO internalaccount VALUES("6","1","115000000000","0","BUILDING CONTRIBUTION","45000","0","45000","C","C");
INSERT INTO internalaccount VALUES("7","1","116000000000","0","LEGAL RESERVES","0","0","0","C","C");
INSERT INTO internalaccount VALUES("8","1","117000000000","0","GRANT","0","0","0","C","C");
INSERT INTO internalaccount VALUES("9","1","118000000000","0","SOLIDARITY FUND","0","0","0","C","C");
INSERT INTO internalaccount VALUES("10","1","119000000000","0","LEAGUE LOAN","0","0","0","C","C");
INSERT INTO internalaccount VALUES("11","1","120000000000","0","STAFF GRATUITY/TRAINING","0","0","0","C","C");
INSERT INTO internalaccount VALUES("12","2","201000000000","0","TELECASH SOFTWARE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("13","2","202000000000","0","SOFTWARE LICENCE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("14","2","203000000000","0","LAND","0","0","0","D","D");
INSERT INTO internalaccount VALUES("15","2","204000000000","0","BUILDING","0","0","0","D","D");
INSERT INTO internalaccount VALUES("16","2","205000000000","0","FURNITURES AND FITTINGS","0","0","0","D","D");
INSERT INTO internalaccount VALUES("17","2","206000000000","0","COMPUTER EQUIPEMENTS","0","0","0","D","D");
INSERT INTO internalaccount VALUES("18","2","207000000000","0","PADMIR MOTORBIKE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("19","2","208000000000","0","OFFICE EQUIPEMENT MOVABLE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("20","2","209000000000","0","INTERNET CONNECTION","0","0","0","D","D");
INSERT INTO internalaccount VALUES("21","2","210000000000","0","SOME INDIVIDUAL ACCOUNT","0","0","0","D","D");
INSERT INTO internalaccount VALUES("22","2","211000000000","0","DAILY SAVING EMPLOYE 1","0","0","0","D","D");
INSERT INTO internalaccount VALUES("23","2","212000000000","0","LEAGUE SHARES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("24","2","213000000000","0","UBC SHARES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("25","2","214000000000","0","LEAGUE FIXED DEPOSIT","0","0","0","D","D");
INSERT INTO internalaccount VALUES("26","2","215000000000","0","PROV FOR INTANGIBLE ASSETS","0","0","0","D","D");
INSERT INTO internalaccount VALUES("27","2","216000000000","0","PROV FOR OFFICE BUILDING","0","0","0","D","D");
INSERT INTO internalaccount VALUES("28","2","217000000000","0","PROV FOR DEP FURN AND FITTINGS","0","0","0","D","D");
INSERT INTO internalaccount VALUES("29","2","218000000000","0","PROVISION FOR COMPUTER","0","0","0","D","D");
INSERT INTO internalaccount VALUES("30","2","219000000000","0","PROV FOR DEP PADMIR BIKE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("31","2","220000000000","0","PROV DEPR OFFICE EQUIPEMENT","0","0","0","D","D");
INSERT INTO internalaccount VALUES("32","3","301000000000","0","NORMAL LOAN","25000","25000","50000","D","D");
INSERT INTO internalaccount VALUES("33","3","311000000000","0","SHORT TERM LOANS","0","0","0","D","D");
INSERT INTO internalaccount VALUES("34","3","312000000000","0","STOCKS OF OTHER SALABLES","0","0","0","","");
INSERT INTO internalaccount VALUES("35","3","313000000000","0","LEAGUE MONEY TRANSFER D","15000","0","15000","","");
INSERT INTO internalaccount VALUES("36","3","314000000000","0","RURAL DEVELOPPEMENT FUND","0","0","0","","");
INSERT INTO internalaccount VALUES("37","3","315000000000","0","DORMANTS ACCOUNTS","0","0","0","","");
INSERT INTO internalaccount VALUES("38","3","316000000000","0","DAILY SAVINGS COLLECTOR 1","49000","10000","59000","C","C");
INSERT INTO internalaccount VALUES("39","3","317000000000","0","DAILY SAVINGS COLLECTOR 2","0","0","0","C","C");
INSERT INTO internalaccount VALUES("40","3","318000000000","0","DAILY SAVINGS COLLECTOR 3","0","0","0","C","C");
INSERT INTO internalaccount VALUES("41","3","319000000000","0","DAILY SAVINGS HEAD OFFICE","0","0","0","C","C");
INSERT INTO internalaccount VALUES("42","3","350000000000","0","MEMBERS DEPOSIT","192500","6500","199000","C","C");
INSERT INTO internalaccount VALUES("43","3","351000000000","0","LOANS TRANSIT ACCOUNT","0","0","0","","");
INSERT INTO internalaccount VALUES("44","3","352000000000","0","MEMBERS SAVINGS","454500","223000","677500","C","C");
INSERT INTO internalaccount VALUES("45","3","353000000000","0","INTEREST ON SAVINGS MEMBER","0","0","0","","");
INSERT INTO internalaccount VALUES("46","3","360000000000","0","INTEREST ON CLOSED ACCOUNTS","0","0","0","","");
INSERT INTO internalaccount VALUES("47","3","370000000000","0","MORTGAGE/CAUTION/MONIT","0","0","0","","");
INSERT INTO internalaccount VALUES("48","3","371000000000","0","PROVISION FOR DOUBTFUL D","0","0","0","","");
INSERT INTO internalaccount VALUES("49","4","439000000000","0","SPECIAL ADVANCES STAFF","0","0","0","","");
INSERT INTO internalaccount VALUES("50","4","440000000000","0","MEMBERS UNIFORMS","2500","0","2500","","");
INSERT INTO internalaccount VALUES("51","4","441000000000","0","TERM DEPOSIT ACCOUNT","0","0","0","","");
INSERT INTO internalaccount VALUES("52","4","442000000000","0","INTERNAL MC","0","0","0","","");
INSERT INTO internalaccount VALUES("53","4","443000000000","0","ONG FUNDS","0","0","0","","");
INSERT INTO internalaccount VALUES("54","4","444000000000","0","OTHER BRANCH DEPOSIT","0","0","0","","");
INSERT INTO internalaccount VALUES("57","4","445000000000","0","SOME DEPOSIT","0","0","0","","");
INSERT INTO internalaccount VALUES("58","4","446000000000","0","INTERBRANCH OFFICE","0","0","0","","");
INSERT INTO internalaccount VALUES("59","4","447000000000","0","LEDGER DIFF","0","0","0","","");
INSERT INTO internalaccount VALUES("60","4","448000000000","0","LEDGER DIFF DURING COMPUT","0","0","0","","");
INSERT INTO internalaccount VALUES("61","4","449000000000","0","MEMBERS GSM DEPOSIT","0","0","0","","");
INSERT INTO internalaccount VALUES("63","4","450000000000","0","MTN GSM DEPOSIT","0","0","0","","");
INSERT INTO internalaccount VALUES("64","4","451000000000","0","UBC/TAXES/ACCOUNT","0","0","0","","");
INSERT INTO internalaccount VALUES("65","4","452000000000","0","EXPENSES PAYABLES","0","0","0","","");
INSERT INTO internalaccount VALUES("67","4","453000000000","0","MTN BILL PAYABLE","0","0","0","","");
INSERT INTO internalaccount VALUES("68","4","454000000000","0","UNIVERSAL FARMER BILL","0","0","0","","");
INSERT INTO internalaccount VALUES("69","4","455000000000","0","INTERNAL MONEY TRANSFER","0","0","0","","");
INSERT INTO internalaccount VALUES("70","4","456000000000","0","PREPAID RENTS","0","0","0","","");
INSERT INTO internalaccount VALUES("71","4","457000000000","0","PREPAID SECURITY EXPENSE","0","0","0","","");
INSERT INTO internalaccount VALUES("72","4","460000000000","0","PROVISION LEDGER DIFFERE","0","0","0","","");
INSERT INTO internalaccount VALUES("73","5","501000000000","0","UBC SAVINGS","0","0","0","","");
INSERT INTO internalaccount VALUES("74","5","502000000000","0","ORANGE MONEY ACCOUNT","0","0","0","","");
INSERT INTO internalaccount VALUES("75","5","503000000000","0","MTN MOBILE MONEY ACCOUNT","0","0","0","","");
INSERT INTO internalaccount VALUES("76","5","504000000000","0","UBA SAVINGS ACCOUNT","0","0","0","","");
INSERT INTO internalaccount VALUES("78","5","505000000000","0","UBA CURRENT","0","0","0","","");
INSERT INTO internalaccount VALUES("79","5","506000000000","0","NFC CAUTION DEPOSIT","0","0","0","","");
INSERT INTO internalaccount VALUES("80","5","507000000000","0","MUTUAL FUNDDEPOSIT","0","0","0","","");
INSERT INTO internalaccount VALUES("81","5","508000000000","0","OTHER BANK ACCOUNT","0","0","0","","");
INSERT INTO internalaccount VALUES("82","5","509000000000","0","BAYELLE CREDIT UNION ACCOUNT","0","0","0","","");
INSERT INTO internalaccount VALUES("83","5","512000000000","0","LEAGU FIXED UP DEP","0","0","0","","");
INSERT INTO internalaccount VALUES("84","5","513000000000","0","LEAGUE REGULAR DEPOSIT","0","0","0","","");
INSERT INTO internalaccount VALUES("86","5","514000000000","0","INSURANCE PREM DEPOSIT","0","0","0","","");
INSERT INTO internalaccount VALUES("87","5","515000000000","0","CASH ON HAND","0","0","0","","");
INSERT INTO internalaccount VALUES("88","6","616000000000","0","INTEREST ON SAVINGS EXP","0","0","0","D","D");
INSERT INTO internalaccount VALUES("89","6","617000000000","0","INTEREST ON LEAGUE LOAN","0","0","0","D","D");
INSERT INTO internalaccount VALUES("90","6","618000000000","0","BANK CHARGES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("91","6","619000000000","0","SALARY COLLECTION EXPEN","0","0","0","D","D");
INSERT INTO internalaccount VALUES("92","6","620000000000","0","LEAGUE LOAN PROCESSING","0","0","0","D","D");
INSERT INTO internalaccount VALUES("93","6","621000000000","0","COST OF STATIONARY SOLD","0","0","0","D","D");
INSERT INTO internalaccount VALUES("94","6","625000000000","0","OFFICE STATIONARIES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("95","6","626000000000","0","PERSONNEL EXPENSE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("96","6","627000000000","0","EMPLOYES NATIONAL SOCIAL","0","0","0","D","D");
INSERT INTO internalaccount VALUES("98","6","629000000000","0","FIRST AID AND DRUGS","0","0","0","D","D");
INSERT INTO internalaccount VALUES("99","6","630000000000","0","ELECTRICITY AND WATER","0","0","0","D","D");
INSERT INTO internalaccount VALUES("100","6","631000000000","0","MOTOCYCLE RUNNING COST","0","0","0","D","D");
INSERT INTO internalaccount VALUES("101","6","632000000000","0","GENERATOR EXPENSES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("102","6","633000000000","0","OFFICE EXPENSE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("103","6","634000000000","0","PHOTOCOPY EXPENSE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("104","6","635000000000","0","COMPUTER EXPENSES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("105","6","636000000000","0","TRANSPORTATION","0","0","0","D","D");
INSERT INTO internalaccount VALUES("106","6","637000000000","0","LOAN RECOVERY EXPENSES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("107","6","638000000000","0","POST AND TELECOMMUNICATION","0","0","0","D","D");
INSERT INTO internalaccount VALUES("108","6","639000000000","0","TERM DEPOSIT INTEREST","0","0","0","D","D");
INSERT INTO internalaccount VALUES("109","6","640000000000","0","REIMBURSEMENT EXPENSE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("110","6","641000000000","0","PUBLIC RELATION EXP","0","0","0","D","D");
INSERT INTO internalaccount VALUES("113","6","642000000000","0","RENTS","0","0","0","D","D");
INSERT INTO internalaccount VALUES("114","6","643000000000","0","LEGAL EXPENSE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("115","6","644000000000","0","MUTUAL  FUND PREMIUM EX","0","0","0","D","D");
INSERT INTO internalaccount VALUES("116","6","645000000000","0","MAINTENANCE AND REPAIRS","0","0","0","D","D");
INSERT INTO internalaccount VALUES("117","6","646000000000","0","PROMOTIONAL EXPENSES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("119","6","647000000000","0","CHAPTER DUES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("120","6","648000000000","0","OTHER OFFICE EXPENSES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("121","6","649000000000","0","LEAGUE DUES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("122","6","650000000000","0","BOARD AND COMM EXP","0","0","0","D","D");
INSERT INTO internalaccount VALUES("123","6","651000000000","0","PROVISIO AGM EXPENSES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("124","6","652000000000","0","INSURANCE PRMIUMS","0","0","0","D","D");
INSERT INTO internalaccount VALUES("125","6","654000000000","0","SEMINAR AND EDUCATION","0","0","0","D","D");
INSERT INTO internalaccount VALUES("126","6","655000000000","0","DAILY SAVING EXPENSE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("127","6","656000000000","0","DAILY SAVING EXPENSES N","0","0","0","D","D");
INSERT INTO internalaccount VALUES("128","6","658000000000","0","SECURITY EXPENSE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("129","6","659000000000","0","TAXE/FNE/HLF/BOB/ETC","0","0","0","D","D");
INSERT INTO internalaccount VALUES("130","6","660000000000","0","ADVANCE COMPANY TAXE","0","0","0","D","D");
INSERT INTO internalaccount VALUES("131","6","661000000000","0","VALUE ADDED(VAT)","0","0","0","D","D");
INSERT INTO internalaccount VALUES("132","6","662000000000","0","DEPRECIATION PROVISIONS","0","0","0","D","D");
INSERT INTO internalaccount VALUES("133","6","663000000000","0","PROVISION FOR DOUBTFUL D","0","0","0","D","D");
INSERT INTO internalaccount VALUES("134","6","669000000000","0","PROVISION EXPENSES","0","0","0","D","D");
INSERT INTO internalaccount VALUES("135","7","720000000000","0","BANK INTEREST","0","0","0","C","C");
INSERT INTO internalaccount VALUES("136","7","721000000000","0","LOAN INTEREST","55000","0","55000","C","C");
INSERT INTO internalaccount VALUES("137","7","722000000000","0","INERNAL MONEY TRANSFER","0","0","0","C","C");
INSERT INTO internalaccount VALUES("139","7","723000000000","0","INTEREST ON SHORT TERM LOANS","0","0","0","C","C");
INSERT INTO internalaccount VALUES("140","7","724000000000","0","PROCESSING FEES","1000","0","1000","C","C");
INSERT INTO internalaccount VALUES("141","7","725000000000","0","DEPOSIT CHARGES","2650","0","2650","C","C");
INSERT INTO internalaccount VALUES("142","7","726000000000","0","SAVING WITHDRAWAL CHARGES","1000","0","1000","C","C");
INSERT INTO internalaccount VALUES("143","7","727000000000","0","SALE OF CAMCCUL STATION","0","0","0","C","C");
INSERT INTO internalaccount VALUES("145","7","728000000000","0","SALE OF CAMCCUL STATION","0","0","0","C","C");
INSERT INTO internalaccount VALUES("146","7","729000000000","0","SALE OF STATIONNARY-OTHER","0","0","0","C","C");
INSERT INTO internalaccount VALUES("147","7","730000000000","0","SALE OF PASSBOOKS","0","0","0","C","C");
INSERT INTO internalaccount VALUES("148","7","731000000000","0","OTHER INCOME","0","0","0","C","C");
INSERT INTO internalaccount VALUES("149","7","732000000000","0","ORANGE MONEY INCOME","3500","0","3500","C","C");
INSERT INTO internalaccount VALUES("150","7","734000000000","0","RISK MANAGMENT INCOME","0","0","0","C","C");
INSERT INTO internalaccount VALUES("151","7","736000000000","0","ENTRANCE FEES","22000","0","22000","C","C");
INSERT INTO internalaccount VALUES("152","7","737000000000","0","DAILY SAVINGS INCOME","0","0","0","C","C");
INSERT INTO internalaccount VALUES("153","7","738000000000","0","DAILY SAVING INCOME COLLECTOR 1","0","0","0","C","C");
INSERT INTO internalaccount VALUES("154","7","740000000000","0","DAILY SAVING INCOME COLLECTOR 2","0","0","0","C","C");
INSERT INTO internalaccount VALUES("155","7","741000000000","0","DAILY SAVINGS INCOME COLLECTOR 3","0","0","0","C","C");
INSERT INTO internalaccount VALUES("156","7","743000000000","0","BAD DEBT RECOVERED","0","0","0","C","C");



DROP TABLE loan;

CREATE TABLE `loan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deadline` date NOT NULL,
  `dateLoan` date NOT NULL,
  `rate` double NOT NULL,
  `loanAmount` bigint(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `monthly_payment` bigint(20) NOT NULL,
  `id_physical_member` int(11) DEFAULT NULL,
  `loancode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `loanprocessingfees` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C5D30D032C63ACC6` (`loancode`),
  KEY `IDX_C5D30D03204E5BEC` (`id_physical_member`),
  CONSTRAINT `FK_C5D30D03204E5BEC` FOREIGN KEY (`id_physical_member`) REFERENCES `member` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO loan VALUES("1","2018-08-31","2017-12-02","2","300000","1","50000","74","BL-10080","0");
INSERT INTO loan VALUES("2","2018-06-02","2017-05-17","2","3000000","1","0","26","BL-10028","0");
INSERT INTO loan VALUES("3","2018-06-02","2017-12-02","2","150000","1","0","73","BL-10079","0");
INSERT INTO loan VALUES("4","2018-06-02","2017-12-02","2","400000","1","50000","71","BL-10077","0");
INSERT INTO loan VALUES("5","2018-06-02","2017-12-02","2","500000","1","50000","70","BL-10076","0");
INSERT INTO loan VALUES("6","2018-06-02","2017-12-02","2","1500000","1","50000","75","BL-10081","0");
INSERT INTO loan VALUES("7","2018-06-02","2017-12-02","2","700000","1","50000","76","BL-10082","0");
INSERT INTO loan VALUES("8","2018-06-02","2017-12-02","2","1000000","1","50000","1","BL-10001","0");
INSERT INTO loan VALUES("9","2018-06-02","2017-12-02","2","220000","0","25000","31","BL-10035","0");
INSERT INTO loan VALUES("10","2018-06-02","2017-11-09","2","1000000","1","25000","45","BL-10049","0");
INSERT INTO loan VALUES("11","2018-06-02","2017-12-02","2","1800000","1","25000","27","BL-10029","0");
INSERT INTO loan VALUES("12","2018-06-02","2017-12-02","2","100000","1","25000","86","BL-10092","0");
INSERT INTO loan VALUES("13","2018-06-02","2017-12-02","2","500000","1","50000","84","BL-10090","0");
INSERT INTO loan VALUES("14","2018-06-02","2017-12-02","2","350000","1","50000","93","BL-10103","0");
INSERT INTO loan VALUES("15","2018-06-02","2017-12-02","2","500000","1","50000","13","BL-10013","0");
INSERT INTO loan VALUES("16","2018-06-02","2017-12-02","2","100000","1","20000","99","BL-10110","0");
INSERT INTO loan VALUES("17","2018-06-02","2017-12-02","2","1200000","1","50000","89","BL-10095","0");
INSERT INTO loan VALUES("18","2018-06-02","2017-12-02","2","300000","1","50000","80","BL-10086","0");
INSERT INTO loan VALUES("19","2018-06-02","2017-12-02","2","3000000","1","50000","101","BL-10112","0");
INSERT INTO loan VALUES("20","2018-06-02","2017-12-02","2","1300000","1","50000","98","BL-10108","0");
INSERT INTO loan VALUES("21","2018-06-02","2017-12-02","2","1500000","1","50000","104","BL-10115","0");
INSERT INTO loan VALUES("22","2018-06-02","2017-12-02","2","1000000","1","50000","109","BL-10120","0");
INSERT INTO loan VALUES("23","2018-06-02","2017-11-21","1","500000","1","50000","108","BL-10119","0");
INSERT INTO loan VALUES("24","2018-06-02","2017-12-02","2","1000000","1","50000","110","BL-10121","0");
INSERT INTO loan VALUES("25","2018-06-05","2017-12-05","1.8","2500000","1","0","2","BL-10002","0");
INSERT INTO loan VALUES("26","2018-06-05","2017-12-05","1.8","200000","1","0","23","BL-10025","0");
INSERT INTO loan VALUES("27","2018-06-05","2017-12-05","1.8","230000","1","0","37","BL-10041","0");
INSERT INTO loan VALUES("28","2018-06-12","2017-12-05","5","200000","1","0","121","BL-10127","0");
INSERT INTO loan VALUES("29","2018-06-12","2017-12-05","5","150000","1","0","122","BL-10128","0");



DROP TABLE loan_history;

CREATE TABLE `loan_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_loan` int(11) DEFAULT NULL,
  `monthlyPayement` bigint(20) NOT NULL,
  `interest` bigint(20) NOT NULL,
  `remainAmount` bigint(20) NOT NULL,
  `newInterest` bigint(20) NOT NULL,
  `dateOperation` date NOT NULL,
  `closeLoan` tinyint(1) DEFAULT NULL,
  `id_currentUser` int(11) DEFAULT NULL,
  `unpaidInterest` bigint(20) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `is_confirmed` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9F5FE3F7124524C` (`id_currentUser`),
  KEY `IDX_9F5FE3F4EF31101` (`id_loan`),
  KEY `IDX_9F5FE3F6B3CA4B` (`id_user`),
  CONSTRAINT `FK_9F5FE3F4EF31101` FOREIGN KEY (`id_loan`) REFERENCES `loan` (`id`),
  CONSTRAINT `FK_9F5FE3F6B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`),
  CONSTRAINT `FK_9F5FE3F7124524C` FOREIGN KEY (`id_currentUser`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO loan_history VALUES("1","2","800000","718314","2200000","0","2017-12-05","1","2","0","","0");
INSERT INTO loan_history VALUES("2","25","0","0","2500000","0","2017-12-05","1","1","2103535","","0");
INSERT INTO loan_history VALUES("3","8","0","0","1000000","0","2017-12-05","1","1","484001","","0");
INSERT INTO loan_history VALUES("4","26","0","0","0","0","2017-12-05","1","1","18660","","0");
INSERT INTO loan_history VALUES("5","27","0","0","0","0","2017-12-05","1","1","70881","","0");
INSERT INTO loan_history VALUES("6","9","0","0","107666","0","2017-11-30","1","1","0","","0");
INSERT INTO loan_history VALUES("7","1","0","0","221713","0","2017-11-12","1","1","108405","","0");
INSERT INTO loan_history VALUES("8","3","69781","2072","80219","0","2017-07-11","1","1","0","","0");
INSERT INTO loan_history VALUES("10","4","0","0","134384","0","2017-11-15","1","1","0","","0");
INSERT INTO loan_history VALUES("11","5","0","0","373809","0","2017-11-25","1","1","0","","0");
INSERT INTO loan_history VALUES("12","6","0","0","1327358","0","2017-11-07","1","1","0","","0");
INSERT INTO loan_history VALUES("13","7","0","0","617933","0","2017-11-15","1","1","0","","0");
INSERT INTO loan_history VALUES("14","11","0","0","1736801","0","2017-11-21","1","1","5052","","0");
INSERT INTO loan_history VALUES("15","12","0","0","25943","0","2017-11-22","1","1","12818","","0");
INSERT INTO loan_history VALUES("16","13","0","0","261461","0","2017-11-17","1","1","0","","0");
INSERT INTO loan_history VALUES("17","14","0","0","149816","0","2017-11-15","1","1","0","","0");
INSERT INTO loan_history VALUES("18","15","0","0","382126","0","2017-11-08","1","1","2691","","0");
INSERT INTO loan_history VALUES("19","28","0","0","142098","0","2017-12-05","1","1","103759","","0");
INSERT INTO loan_history VALUES("24","29","0","0","97045","0","2017-11-17","1","1","866","","0");
INSERT INTO loan_history VALUES("25","16","1480","520","24541","0","2017-11-04","1","1","0","","0");
INSERT INTO loan_history VALUES("26","17","0","0","540278","0","2017-10-31","1","1","10789","","0");
INSERT INTO loan_history VALUES("27","18","0","0","150706","0","2017-11-30","1","1","15570","","0");
INSERT INTO loan_history VALUES("28","19","0","0","2507050","0","2017-11-30","1","1","31296","","0");
INSERT INTO loan_history VALUES("29","20","0","0","1294032","0","2017-11-07","1","1","5514","","0");
INSERT INTO loan_history VALUES("30","21","0","0","850000","0","2017-11-30","1","1","0","","0");
INSERT INTO loan_history VALUES("31","22","0","0","412875","0","2017-11-23","1","1","0","","0");



DROP TABLE loan_parameter;

CREATE TABLE `loan_parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parameter` int(11) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_or_modified_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_6BC4AD8A2A979110` (`parameter`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO loan_parameter VALUES("1","3","This number means that all the loan of the credit will be made for those which their saving and share are at least times 3. For example if you want a loan of 300000 \nF CFA, the amount in your share and saving accounts have to be at least 100000","2017-12-11 05:56:11");



DROP TABLE member;

CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sex` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `dateOfBirth` date DEFAULT NULL,
  `placeOfBirth` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nicNumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issuedOn` date DEFAULT NULL,
  `issuedAt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `proposedBy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isAproved` tinyint(1) DEFAULT NULL,
  `aprovedBy` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `memberNumber` bigint(20) DEFAULT NULL,
  `doneAt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `membershipDateCreation` date DEFAULT NULL,
  `witnessName` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phoneNumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registration_fees` bigint(20) NOT NULL,
  `building_fees` bigint(20) NOT NULL,
  `share` bigint(20) NOT NULL,
  `saving` bigint(20) NOT NULL,
  `deposit` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_70E4FA787925630D` (`memberNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO member VALUES("1","MELI BLAISE NSONKWA","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","BOARD OF DIRECTORS","10001","BAMENDA","2017-12-01","//","//","8000","15000","20000","125000","90000");
INSERT INTO member VALUES("2","TCHOUALA HONORE BARNABE","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","BOARD DIRECTORIES","10002","//","2017-12-01","//","//","6000","10000","18000","13000","1000");
INSERT INTO member VALUES("3","TSAYEM MOISE","Male","2017-12-01","//","//","///","//","2017-12-01","//","///","1","//","10003","//","2017-12-01","//","//","6000","10000","0","2500","0");
INSERT INTO member VALUES("4","LONTSI BONAVENTURE","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","//","10004","//","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("5","TEDONGMO ROSTAND","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","//","10005","//","2017-12-01","//","//","6000","10000","319000","201500","21500");
INSERT INTO member VALUES("6","TCHOFFO ERNEST","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","//","10006","//","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("7","DJIALAC DOUANLA ISAAC","Male","1976-04-20","NKWEN","Trader","Bamenda","111484399","2011-12-21","NW01","///","1","BOARD OF DIRECTORS","10007","BAMENDA","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("8","LAPA VINCENT","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","BOARD OF DIRECTORS","10008","//","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("9","TEMCHIN JEAN PAUL","Male","2017-12-01","//","//","//","//","2017-12-01","//","///","1","BOARD OF DIRECTORS","10009","BAMENDA","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("10","DOUNGMO ROGER","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","//","10010","//","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("11","SONWA JEAN","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","BOARD OF DIRECTORS","10011","BAMENDA","2017-12-01","//","//","6000","10000","0","-2500","0");
INSERT INTO member VALUES("12","FOPA JEAN","Male","1946-06-04","BALACTHI","TRADER","NKWEN","108588857","2009-05-28","NW01","//","1","BOARD OF DIRECTORS","10012","BAMENDA","2017-12-01","//","//","0","0","0","0","0");
INSERT INTO member VALUES("13","TIWA LIONIE MARIANE","Female","2017-12-01","//","//","//","//","2017-12-01","//","//","1","//","10013","//","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("14","LANDO APPOLINAIRE","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","BOARD OF DIRECTORS","10014","BAMENDA","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("15","DOUANLA MAGELLAN","Male","2017-12-01","///","//","//","//","2017-12-01","//","//","1","//","10015","//","2017-12-01","//","/","5000","10000","0","0","0");
INSERT INTO member VALUES("16","TIAYA MARTINE","Female","1963-07-17","BALATCHI","Trader","Mille 3 Nkwen Bamenda","106794097","2005-11-07","NW19","","1","","10017","","2017-12-01","","","6000","10000","0","0","0");
INSERT INTO member VALUES("17","YEMELONG SIMON","Male","1954-07-12","BALACTCHI","Trader","Bamenda","//","2017-12-01","//","//","1","BOARD OF DIRECTORS","10018","Bamenda","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("18","DONGMO BERTRAND","Male","1971-11-11","BALATCHI","Technician","Bamenda","110641386","2016-08-31","Bamenda","//","1","BOARD OF DIRECTORS","10019","Bamenda","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("19","TEDONGMOUO DJOUSSE VIVIANE","Female","1976-07-08","MBOUDA","Trader","Bamenda","105915290","2004-10-19","Bamenda","//","1","BOARD OF DIRECTORS","10020","Bamenda","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("20","AYEMENE NGOULA ELVIS","Male","1995-12-16","BALATCHI","TRADER","BAMENDA NKWEN","///","2017-12-01","//","//","1","BOARD OF DIRECTORS","10021","BAMENDA","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("21","MELACHIO KEANFE CARLOS","Male","2017-12-01","//","Trader","677125258","107265808","2017-12-01","//","//","1","BOARD OF DIRECTORS","10022","Bamenda","2017-12-01","//","677125258","0","10000","0","0","0");
INSERT INTO member VALUES("22","MANFOUO HORTENSE","Female","1979-03-05","MBOUDA","Trader","679921634","111112511","2017-12-01","///","//","1","BOARD OF DIRECTORS","10023","Bamenda","2017-12-01","//","679921634","6000","0","0","0","0");
INSERT INTO member VALUES("23","AZANGUE TCHOUALA MATHIEU","Male","1956-01-01","BALATCHI","Trader","Mille 3 Bamenda","//","2017-12-01","//","//","1","BOARD OF DIRECTORS","10025","Bamenda","2017-12-01","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("24","YUH EFFICIENCE TOYEIN","Male","1990-01-01","FUANANTUI","Welder","NKWEN","106437459","2011-02-12","NW01","//","1","BOARD OF DIRECTORS","10026","Bamenda","2017-12-01","//","677996882","6000","0","0","0","0");
INSERT INTO member VALUES("25","TIMGUM KENNETH","Male","1983-04-13","BELLO","TRADER","//","107816149","2007-08-17","NW03","//","1","BOARD OF DIRECTORS","10027","Bamenda","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("26","NWEKE EMMANUEL","Male","1986-12-25","Nigeria","Trader","Mille 2 NKWEN","1150517417","2017-12-01","//","//","1","BOARD OF DIRECTORS","10028","Bamenda","2017-12-01","//","679671219","6000","0","0","0","0");
INSERT INTO member VALUES("27","FEUGUEU WOULAHIE DONACIEN","Male","1981-05-13","MBOUDA","MECANIC","BAMENDA","109683237","2010-04-15","OU30","TEDONGMO ROSTAND","1","BOARD OF DIRECTORS","10029","BAMENDA","2017-12-01","","","6000","0","0","0","0");
INSERT INTO member VALUES("28","KAFONG LUDOVIL","Male","1986-11-12","BATCHAM","//","BAMENDA","107524244","2008-04-04","OU12","//","1","BOARD OF DIRECTORS","10032","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("29","KIMENG AUGUSTIN KONFOR","Male","1973-05-17","ALBUKAM BAMENDA","CIVIL ENGENEER","BAMENDA NKWEN","104997268","2004-10-25","NW01","//","1","BOARD OF DIRECTORS","10033","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("30","KENNE  DONGLA MASMINE// MELI MARIE CLAIRE","Female","1994-05-06","BALATCHI","//","BAMENDA","//","2017-12-01","//","//","1","BOARD OF DIRECTORS","10034","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("31","TANKFU RENE GEUN","Male","1985-05-06","BAMENDA","Trader","Mille 2 Nkwen Bamenda","//","2017-12-01","//","//","1","BOARD OF DIRECTORS","10035","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("32","TIMAKAI DANIEL","Male","1980-12-29","NKWEN","ELECTONICS","NKWEN","101670575","2001-06-11","NW19","S. JUSPHINE","1","BOARD OF DIRECTORS","10036","BAMENDA","2017-12-01","","//","6000","0","0","0","0");
INSERT INTO member VALUES("33","FOMENE KUETE DUPLEX","Male","1978-03-17","BAMENDA","TRADER","BAMENDA","10873246","2008-10-22","NW02","//","1","BOARD OF DIRECTORS","10037","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("34","DEHEZO ROSALINE","Female","1966-07-15","BALARCHI","TRADER","NEW LAY OUT","106237803","2011-03-14","NW01","NZELIO DJOUDA CHARLEINE","1","BOARD OF DIRECTORS","10038","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("35","SAHNYUY JUSPHINE KIJIKA","Female","1984-10-23","KUMBO","//","Mille 3 Bamenda","114419979","2013-01-09","NW11","SAHNYUY JUSPHINE KIJIKA","1","BOARD DIRECTORIES","10039","BAMENDA","2017-12-01","//","//","0","0","0","0","0");
INSERT INTO member VALUES("36","ASAHA TIDO BERTO","Male","1991-03-21","BAMUNKA","TRADER","NKWEN BAMENDA","109331407","2009-12-02","NW01","NZELIO DJOUDA CHARLEINE","1","BOARD DIRECTORIES","10040","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("37","NEMBOT MARIUS","Male","1979-07-05","DOUALA","INFORMATICIAN","DOUALA","107887206","2007-06-19","LT08","//","1","BOARD DIRECTORIES","10041","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("38","TCHINDA ZANGUE IDRISS","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","BOARD DIRECTORIES","10042","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("39","NGOULA TAPA BERTRAND D","Male","1972-06-24","BALATCHI","TRADER","BEBE ELECTONIC COMERCIAL AVENUE","106851397","2015-11-09","NW01","//","1","//","10043","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("40","DZEKEMTAAH MUHAMADU NORUDIN","Male","1995-10-03","WAINAMAH","Saler","Bayelle","113335950","2011-10-03","NW19","TSAYEM MOISE","1","BOARD DIRECTORIES","10044","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("41","AKUMBOM CELESTINE TOH","Female","1990-07-17","KEDJOM KEKU","BUILDER","MILLE 3 NKWEN","113597601","2011-09-03","NW19","TSAYEM MOISE","1","BOARD DIRECTORIES","10045","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("42","SAHA TIAYO GERSINO","Male","1981-02-04","MBOUDA","//","691242101","110641478","2010-09-20","NW02","SAHNYUY JUSPHINE KIJIKA","1","BOARD DIRECTORIES","10046","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","670082201","6000","0","0","0","0");
INSERT INTO member VALUES("43","MELI LECPA FABRICE","Male","1994-06-30","BAMENDA","STUDENT","BAMENDA","113690331","2012-12-04","NW02","TSAYEM MOISE","1","BOARD DIRECTORIES","10047","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("44","NIBA CLETUS","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","//","10048","//","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("45","DJOUSSE JEANNOT","Male","1974-07-26","BALATCHI","TRADER","BAMENDA","109250863","2009-11-05","","NZELIO D.  CHARLEINE","1","BOARD DIRECTORIES","10049","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("46","LIVINUS LOLIKA","Male","1995-06-14","//","","Mille 3 ","111734000","2011-11-16","NW10","SAHNYUY JUSPHINE KIJIKA","1","BOARD DIRECTORIES","10050","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("47","NASABE VENATUS","Male","1968-02-04","MENDANKWE","SCIEUR","BAMENDA","106660331","2005-05-31","//","TCHOULA HONORE","1","BOARD DIRECTORIES","10051","BAMENDA","2017-12-01","SAHNUYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("48","NDOUNGMO KENNE PASCAL","Male","1977-01-01","//","//","//","//","2017-12-01","//","//","1","//","10053","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("49","FANKA FABRIS CIBEFEH ","Male","1989-08-28","NKWEN","STUDENT","MILLE 3","1166595491","2014-09-02","NW19","SAHNYUY JUSPHINE KIJIKA","1","BOARD DIRECTORY","10054","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("50","IRINE NEIGHEMCHUNG","Female","1978-01-01","OKU","TRADER","670256392","110011505","2011-06-30","NW02","CHARLAINE","1","BOARD DIRECTORY","10055","BAMENDA","2017-12-01","//","670256392","6000","10000","0","0","0");
INSERT INTO member VALUES("51","NENDA","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","BOARD DIRECTORY","10056","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("52","IOTA TADIDAS ELVIS","Male","1982-08-12","BAMENDA","TRADER","SISIA","1148540687","2013-07-29","NW19","//","1","BOARD DIRECTORY","10057","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("53","NTOMBONG MARINUS B","Male","1991-03-02","BAMENDA","TECHNICIAN","MILLE 3","110863403","2011-06-29","NW01","SAHNYUY JUSPHINE KIJIKA","1","BOARD DIRECTORY","10058","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("54","TIOMELA JEAN CLAUDE","Male","1970-04-27","BAMENDA","TRADER","NKWEN","109601186","2010-08-12","NW01","NZELIO DJOUDA CHARLEINE","1","BOARD DIRECTORY","10059","BAMENDA","2017-12-01","NDZELIO DJOUDA CHARLEINE","//","6000","0","0","0","0");
INSERT INTO member VALUES("55","NOELEKOUO IOTA ANGE","Male","2008-01-03","YAOUNDE","//","SISIA","//","2017-12-01","//","//","1","BOARD DIRECTORY","10060","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("56","POUOKEM VICTOR","Male","1970-09-13","BALATCHI","DRIVER","LAFE","111479560","2011-07-17","OU36","NGOULA T. BERTRAND","1","BOARD DIRECTORY","10061","BAMENDA","2017-12-01","NGOULA T. BERTRAND","//","6000","0","0","0","0");
INSERT INTO member VALUES("57","TSAGUE GILBERT","Male","2017-12-01","DANGANG","TRADER","BAMENDA","110635547","2010-09-20","NW02","SAHNYUY JUSPHINE KIJIKA","1","BOARD DIRECTORY","10062","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("58","BONGLIM SYLVANUS YUNGSI","Male","1988-04-23","DJOTTIN","SHOE MAKER","MILLE 2","106553297","2006-09-04","NW24","SAHNYUY JUSPHINE KIJIKA","1","BOARD DIRECTORY","10063","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("59","KENNE TIA CHARLES B.","Male","1975-05-22","BALATCHI","TRADER","NKWEN","107607956","2007-03-15","NW01","//","1","BOARD DIRECTORY","10064","BAMENDA","2017-12-01","//","///","6000","0","0","0","0");
INSERT INTO member VALUES("60","WEYEGHO PASCAL HONORE","Male","1968-05-23","MBOUDA","BUSNNESS MAN","BAMENDA","115051750","2013-06-12","NW01","//","1","BOARD DIRECTORY","10065","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("61","WOGINO JUSTINA","Female","1959-06-13","AWING","TRADER","BAMENDA","117353016","2014-09-05","NW02","//","1","BOARD DIRECTORY","10067","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("62","KEMATIO DOUANLA ISAAC ","Male","1980-06-23","BALATCHI","TRADER","BAMENDA","114157781","2013-02-19","NW02","//","1","BOARD DIRECTORY","10068","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("63","HENRY SHAMBO NFORMI","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","BOARD DIRECTORY","10069","//","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("64","TCHINDA NGOULA CHARLOTTE","Female","1991-01-20","MBOUDA","STUDENT","BAMENDA","111079516","2011-06-06","OU30","//","1","BOARD DIRECTORY","10070","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","///","6000","10000","0","0","0");
INSERT INTO member VALUES("65","ROLAND NCHANJI TANTOH","Male","1970-08-09","MANKON","TECHNICIAN","BAMENDA","111099027","2011-05-16","NW02","//","1","BOARD DIRECTORY","10071","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("66","SOPTSI PHILIPPE","Male","1960-01-26","BANGAM","CONTROLER","BAMENDA","109634646","2010-04-11","NW01","KEUGUEU","1","BOARD DIRECTORY","10072","BAMENDA","2017-12-01","KUEGUEU","//","6000","0","20000","25000","15000");
INSERT INTO member VALUES("67","MACHUPTECH RINGOBELL","Male","1995-03-28","BABANGKI","Busness","Mille 6 Nkwen","1171807235","2016-03-16","NW19","TEDONGMO ROSTAND","1","BOARD DIRECTORY","10073","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("68","ALPHONES NJIOGFUO TUNGANYA","Male","2017-12-01","//","//","Mile 3","107681846","2017-12-01","///","SAHNYUY JUSPHINE KIJIKA","1","BOARD DIRECTORY","10074","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","2000","0","0","0","0");
INSERT INTO member VALUES("69","KEUGUEU CELINE","Male","1986-08-08","BANGANG","//","//","1176499003","2015-06-24","NW01","//","1","BOARD DIRECTORY","10075","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("70","FOKOU WONOU ERIC","Male","1978-11-06","BAMENDA","DRIVER","BAMENDA","119295042","2009-07-01","NW02","TANKFU RENE","1","BOARD DIRECTORY","10076","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("71","FOUOFIE SONKOUE IDRISS","Male","1995-11-06","BAMENDA","STUDENT","//","110328116","2011-06-30","NW01","SAHNYUY JUSPHINE KIJIKA","1","BOARD DIRECTORY","10077","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","10000","0","0","0");
INSERT INTO member VALUES("72","DOUANLA EMMANUEL","Male","1973-12-24","BALATCHI","TRADER","AFTER ST PAUL CATHOLIC CHURCH NKWEN","108008566","2008-01-29","NW19","SAHNYUY JUSPHINE KIJIKA","1","BOARD DIRECTORY","10078","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("73","BOUZEKO SAHA TAPING EMMANUEL","Male","1975-11-04","BALATCHI","TRADER","NEW LAY OUT BAMENDA","115468638","2012-06-12","NW02","TANKFU RENE","1","BOARD OF  DIRECTORS","10079","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","///","6000","0","0","0","0");
INSERT INTO member VALUES("74","MEGHOUPI GABRIEL DESIRE","Male","1979-03-16","MELONG","TRADER","672531717","1136794145","2013-02-04","//","KUEGUEU CELINE","1","BOARD OF DIRECTORS","10080","BAMENDA","2017-12-01","KEUGUEU","672531717","6000","0","20000","25000","0");
INSERT INTO member VALUES("75","MARCEL SAYOH","Male","2017-12-01","//","TRADING","BAMENDA","109287317","2009-10-06","NW02","TANKFU RENE","1","BOARD OF DIRECTORS","10081","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("76","KENVO BONIFACE","Male","1969-06-06","BALATCHI","TRADING","SISIA","113337337","2011-10-01","NW19","TANKFU RENE","1","BOARD OF DIRECTORS","10082","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","10000","0","0","0");
INSERT INTO member VALUES("77","KANA YEMELE CLOVIS","Male","1988-03-30","BAMENDOU","STUDENT","BAMENDA","111332151","2011-03-24","OU17","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIECTORS","10083","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("78","EYANGUI ALFRED DE SAINT PROSPER","Male","1984-08-06","MPALOMPOAM","GEOTECHNICIEN","KOUAMBANI","106518347","2008-12-14","ES09","TCHOUALA BARNABAS HONORE","1","BOARD OF DIRECTORY","10084","BAMENDA","2017-12-01","KEUGUEU CELINE","//","6000","0","0","0","0");
INSERT INTO member VALUES("79","NKONGLACK  TANGMO HELENE","Male","1963-01-01","//","BUSNESS","T. JUNCTION BAMENDA","114058424","2013-02-18","NW19","//","1","BOARD OF DIRECTORS","10085","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("80","KEMTO TEPONNO CYRILLE","Male","1987-07-07","MBOUDA","TRADER","BANDJA STREET BAMENDA","1187861446","2015-12-10","NW02","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10086","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("81","AKEH ESTELLAR","Female","2017-12-01","//","//","//","//","2017-12-01","//","MELI NSONKWA BLAISE","1","BOARD OF DIRECTORS","10087","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("82","KILA HENDRIATA TATAH","Male","1966-05-25","BANSO","TRADER","MILLE 3","116559427","2014-06-18","NW19","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10088","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("83","YEM DESIRE","Male","2017-12-01","KUM","PLUMBER","S BEN MILLE 3","116061121","2013-06-11","NW02","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10089","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("84","NGANG MBENG FELIX","Male","1977-10-02","LOUM","TRADING","NTASEN","115544192","2012-08-28","NW19","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10090","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("85","KEBOU BELMONDO","Male","1989-01-02","BALATCHI","TRADING","SISSIA NKWEN","1155762630","2012-09-18","NW19","TANKFU RENE","1","BOARD OF DIRECTORS","10091","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("86","PIBENG THIOPHILUS","Male","1989-09-25","//","TRADING","SISSIA","113857661","2012-08-09","LT03","//","1","BOARD OF DIRECTORS","10092","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("87","NGOUNOU LEOCADINE MITALE","Female","1991-02-12","NKONGSAMBA","","SISSIA II NKWEN","110632220","2010-10-13","LT26","//","1","BOARD OF DIRECTORS","10093","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","10000","0","0","0");
INSERT INTO member VALUES("88","DJIMELI GHISLAIN","Male","1981-10-02","BALATCHI","TRADER","BAMENDA","1171274151","2015-09-07","SW03","TCHOUALA BARNABAS","1","BOARD OF DIRECTORS","10094","BAMENDA","2017-12-01","NGOUNOU LEOCADINE M.","//","6000","0","0","0","0");
INSERT INTO member VALUES("89","NCHINDA GODLOVE NDIFOR","Male","1980-03-20","BAMENDA-NKWEN","BUSNESS MAN","MILLE 6","117315270","2015-05-25","NW01","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10095","BAMENDA","2017-12-01","//","//","16000","10000","20000","25000","30000");
INSERT INTO member VALUES("90","ABDULAI ADAMU YUNIWO","Male","1989-10-16","NORTH WEST","TRADING","NDAMUKONG BAMENDA","118214520","2016-01-04","NW02","TANKFU RENE","1","BOARD OF DIRECTORS","10097","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("91","DJOUSSE SIMON","Male","1962-01-03","BANGANG","TRADER","SISSIA","110069799","2010-12-13","NW02","//","1","BOARD OF DIRECTORS","10098","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","10000","0","0","0");
INSERT INTO member VALUES("92","TIWA DJIMELI HINAWT ","Male","1983-09-02","BALATCHI","TRADING","BELOW FONCHA","1187981155","2016-02-07","//","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10102","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","","6000","0","0","0","0");
INSERT INTO member VALUES("93","BONGSISI AJARA","Female","1986-11-05","BANSO","TRADING","SISIA","114157899","2013-02-19","NW02","NGOUNOU LEOCADINE","1","BOARD OF DIRECTORS","10103","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","","6000","0","0","0","0");
INSERT INTO member VALUES("94","MANE ROSALINE","Female","1971-08-10","BANGANG","TRADER","SISIA 4","116227379","2013-06-11","NW02","","1","BOARD OF DIRECTORS","10104","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","","6000","0","0","0","0");
INSERT INTO member VALUES("95","DETIO TCHOUPOU MERLAIN","Male","1996-09-17","BAMOUGONG","TRADING","SISIA","113691850","2013-05-13","NW02","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10105","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","","6000","0","0","0","0");
INSERT INTO member VALUES("96","LABAN AVITUS TATA","Male","1989-01-01","DJOTTIN","BUILDER","TABESSI","111143805","2011-04-13","NW25","NGOUNOU LEOCADINE MITALE","1","BOARD OF DIRECTORS","10106","BAMENDA","2017-12-01","NGOUNOU LEOCADINE MITALE","","6000","0","0","0","0");
INSERT INTO member VALUES("97","WIYUKA DERICK SHANG","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","//","10107","//","2017-12-01","//","//","4000","0","0","0","0");
INSERT INTO member VALUES("98","CHO TUMASSANG AKUM TOK ELVIS","Male","1979-12-25","AKUM MEZAM","BUSINNES","NGENG JUNCTION","113975133","2013-01-08","","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10108","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("99","NJOLAI MARIE CLAIRE LIFOTER","Female","1989-12-01","BANSO","TRADER","NEW LAY OUT","111511852","2011-08-26","NW02","NGOUNOU LEOCADINE MITALE","1","BOARD OF DIECTORS","10110","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("100","NCHE MICHAEL NJI","Male","1956-05-12","MANICO","TRADER","//","111482536","2011-09-09","NW02","//","1","BOARD OF DIRECTORS","10111","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","10000","0","0","0");
INSERT INTO member VALUES("101","NKENGLE FACK","Male","1973-06-30","//","TRADER","//","11964674431","1973-06-30","BAMENDA","TANKFU RENE","1","BOARD OF DIRECTORS","10112","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("102","NANG PASCAL NSOM","Male","2017-12-01","//","//","//","//","2017-12-01","//","//","1","BOARD OF DIRECTORS","10113","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("103","DJOUSSE NGINTEDEM DORIS","Female","1985-10-01","BAMENDA","TRADING","BANJAH STREET","108352537","2008-06-12","NW12","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10114","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("104","TSAGOU TEPONNO ALLANDRIX","Male","1987-04-07","BALATCHI","TRADING","//","000497993","2017-02-03","NW01","//","1","BOARD OF DIRECTORS","10115","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("105","FOSSO NGOKO LIVINE","Female","1992-03-28","MBOUDA","TAILORING","NEW ROAD","113597913","2011-10-04","NW19","//","1","BOARD OF DIECTORS","10116","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("106","NAPOLEON BUGHE NIKIA","Male","1974-03-17","BAMESSING","//","FONCHA JUNCTION","1192967167","2016-04-07","TOBOH","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10117","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("107","KENVO MBOUNA ROGER","Male","1978-12-07","BALATCHI","TRADER","BAMENDA","116059848","2014-03-13","NW01","//","1","BOARD OF DIRECTORS","10118","BAMENDA","2017-12-01","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("108","GILBERT TAMFU","Male","2017-12-01","//","DRIVER","//","113858727","2012-10-12","NW01","NGOUNOU LEOCADINE MITALE","1","BOARD OF DIRECTORS","10119","BAMENDA","2017-12-01","NGOUNOU LEOCADINE .","//","6000","0","0","0","0");
INSERT INTO member VALUES("109","METCHOUYERE TEPONNO PHELICIEN","Male","1982-05-22","MBOUDA","TRADING","MILLE 5","108660609","2008-07-18","NW01","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10120","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("110","MABE RITA CHE","Female","1991-10-12","MANKON BAMENDA","TRADER","MILLE 5 NKWEN","KIT311","2011-05-18","NW01","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10121","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("111","DJUIPAT NADEGE","Female","1984-04-06","MBOUDA","TRADING","UP STATION BAMENDA","11723821","2015-08-06","NW02","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10122","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("112","SAA TCHINDA SALVADOR DALY","Female","1994-05-22","MBOUDA-BALATCHI","MECHANIC","MILE","11435150","2013-01-08","NW19","//","1","BOARD OF DIRECTORS","10123","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("113","SHEY ZULAITU RINGNYU","Female","1986-09-16","KAKAR","FARMING","MBANGONG MBANNG","1129375501","2011-09-08","NW12","TANKFU RENE GUEN","1","BOARD OF DIRECTORS","10124","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("114","KUEFFOUO TIAYO BAUDELAIRE","Male","1990-01-21","MBOUDA","PHYSIOTHERAPIST","NEW LAY OUT","110640364","2010-09-09","CE09","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10125","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","6000","0","0","0","0");
INSERT INTO member VALUES("115","TCHINDA SONRAFOUO CELINE","Female","1982-06-22","BANGANG","TRADING","MILLE 3","117778370","2014-11-10","NW02","SAHNYUY JUSPHINE KIJIKA","1","BOARD OF DIRECTORS","10126","BAMENDA","2017-12-01","SAHNYUY JUSPHINE KIJIKA","//","8000","0","15000","20000","30000");
INSERT INTO member VALUES("116","ASTIE MOZONE R.","Male","2017-12-02","//","//","//","//","2017-12-02","//","//","1","//","10030","//","2017-12-02","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("117","LUFONG EMMERENCIA","Female","2017-12-02","//","//","//","//","2017-12-02","//","//","1","BOARD OF DIRECTORS","10031","BAMENDA","2017-12-02","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("118","ALI MOHAMAND","Male","2017-12-02","//","//","//","//","2017-12-02","//","//","1","BOARD OF DIRECTORS","10096","BAMENDA","2017-12-02","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("119","LANDO MELI BERNADETTE","Female","2017-12-02","//","//","//","//","2017-12-02","//","//","1","BOARD OF DIRECTORS","10099","BAMENDA","2017-12-02","//","//","6000","0","0","0","0");
INSERT INTO member VALUES("120","SOFFACK ROSALINE","Female","2017-12-02","//","//","//","//","2017-12-02","//","//","1","BOARD OF DIRECTORS","10100","//","2017-12-02","//","//","6000","10000","0","0","0");
INSERT INTO member VALUES("121","TIABOU MEWA BORICE","Male","2017-12-05","//","//","//","//","2017-12-05","//","//","1","//","10127","//","2017-12-05","//","//","0","0","0","0","0");
INSERT INTO member VALUES("122","SONKOUE DOUALA","Male","2017-12-05","//","//","//","//","2017-12-05","//","//","1","//","10128","//","2017-12-05","//","//","0","0","0","0","0");
INSERT INTO member VALUES("124","test","Male","2017-12-16","//","TRADER","//","","2017-12-16","//","//","1","BOARD OF DIRECTORS","1000254","BAMENDA","2017-12-16","//","//","6000","0","20000","10000","0");
INSERT INTO member VALUES("125","Elsha","Male","2017-12-19","//","TRADER","//","789","2017-12-19","//","//","1","BOARD OF DIRECTORS","78","BAMENDA","2017-12-19","//","//","0","0","0","0","0");
INSERT INTO member VALUES("126","","Male","2017-12-19","//","TRADER","//","","2017-12-19","//","//","1","BOARD OF DIRECTORS","0","BAMENDA","2017-12-19","//","//","0","0","0","0","0");
INSERT INTO member VALUES("127","zaezaezae","Male","2017-12-19","//","TRADER","//","sfsdfsd","2017-12-19","//","//","1","BOARD OF DIRECTORS","555454454","BAMENDA","2017-12-19","//","//","0","0","0","0","0");
INSERT INTO member VALUES("128","dsqdsq","Male","2017-12-19","//","TRADER","//","","2017-12-19","//","//","1","BOARD OF DIRECTORS","74544535463","BAMENDA","2017-12-19","//","//","0","0","0","0","0");
INSERT INTO member VALUES("129","azazsqd","Male","2017-12-19","//","TRADER","//","4565","2017-12-19","//","//","1","BOARD OF DIRECTORS","9798768","BAMENDA","2017-12-19","//","//","0","0","0","0","0");



DROP TABLE operation;

CREATE TABLE `operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` bigint(20) NOT NULL,
  `dateOperation` datetime NOT NULL,
  `id_user_confirmed` int(11) DEFAULT NULL,
  `type_operation` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `is_confirmed` tinyint(1) NOT NULL,
  `member` int(11) DEFAULT NULL,
  `balance` bigint(20) NOT NULL,
  `is_share` tinyint(1) NOT NULL,
  `is_saving` tinyint(1) NOT NULL,
  `is_deposit` tinyint(1) NOT NULL,
  `id_internalAccount` int(11) DEFAULT NULL,
  `representative` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1981A66D6B3CA4B` (`id_user`),
  KEY `IDX_1981A66D70E4FA78` (`member`),
  KEY `IDX_1981A66DF5C0EF3E` (`id_user_confirmed`),
  KEY `IDX_1981A66DDE00AE4` (`id_internalAccount`),
  CONSTRAINT `FK_1981A66D6B3CA4B` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`),
  CONSTRAINT `FK_1981A66D70E4FA78` FOREIGN KEY (`member`) REFERENCES `member` (`id`),
  CONSTRAINT `FK_1981A66DDE00AE4` FOREIGN KEY (`id_internalAccount`) REFERENCES `internalaccount` (`id`),
  CONSTRAINT `FK_1981A66DF5C0EF3E` FOREIGN KEY (`id_user_confirmed`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO operation VALUES("1","25000","2017-12-17 05:10:40","","CASH IN","1","1","","40000","0","0","0","38","");
INSERT INTO operation VALUES("2","85000","2017-12-17 12:46:42","","CASH IN","1","1","1","125000","0","1","0","","");
INSERT INTO operation VALUES("3","20000","2017-12-17 12:46:42","","CASH IN","1","1","1","20000","1","0","0","","");
INSERT INTO operation VALUES("4","30000","2017-12-17 12:46:42","","CASH IN","1","1","1","90000","0","0","1","","");
INSERT INTO operation VALUES("5","2000","2017-12-17 12:46:42","","CASH IN","1","1","1","2450","0","0","0","141","");
INSERT INTO operation VALUES("6","5000","2017-12-18 12:46:42","","CASH IN","1","1","1","45000","0","0","0","6","");
INSERT INTO operation VALUES("7","2000","2017-12-18 12:46:42","","CASH IN","1","1","1","20000","0","0","0","151","");
INSERT INTO operation VALUES("8","25000","2017-12-18 03:06:05","","CASH OUT","2","1","5","-21000","0","1","0","","");
INSERT INTO operation VALUES("9","25000","2017-12-18 03:06:06","","CASH OUT","2","1","5","189500","0","0","0","44","");
INSERT INTO operation VALUES("10","5000","2017-12-18 03:06:06","","CASH OUT","2","1","5","-3500","0","0","1","","");
INSERT INTO operation VALUES("11","5000","2017-12-18 03:06:06","","CASH OUT","2","1","5","122500","0","0","0","42","");
INSERT INTO operation VALUES("12","0","2017-12-18 07:18:22","","CASH OUT","1","1","","0","0","0","0","15","");
INSERT INTO operation VALUES("13","250000","2017-12-18 07:23:57","","CASH IN","1","1","5","229000","0","1","0","","TEDONGMO ROSTAND");
INSERT INTO operation VALUES("14","300000","2017-12-18 07:23:57","","CASH IN","1","1","5","319000","1","0","0","","TEDONGMO ROSTAND");
INSERT INTO operation VALUES("15","25000","2017-12-18 07:23:57","","CASH IN","1","1","5","21500","0","0","1","","TEDONGMO ROSTAND");
INSERT INTO operation VALUES("16","2500","2017-12-18 07:45:16","","CASH IN","1","1","","2500","0","0","0","50","");
INSERT INTO operation VALUES("17","2500","2017-12-18 07:47:21","","CASH OUT","1","1","5","226500","0","1","0","","TEDONGMO ROSTAND");
INSERT INTO operation VALUES("18","2500","2017-12-18 07:47:21","","CASH OUT","1","1","5","437000","0","0","0","44","TEDONGMO ROSTAND");
INSERT INTO operation VALUES("19","15000","2017-12-18 09:22:16","","CASH IN","1","1","","15000","0","0","0","35","");
INSERT INTO operation VALUES("20","25000","2017-12-18 09:52:12","","CASH IN","1","1","66","25000","0","1","0","","SOPTSI PHILIPPE");
INSERT INTO operation VALUES("21","20000","2017-12-18 09:52:13","","CASH IN","1","1","66","20000","1","0","0","","SOPTSI PHILIPPE");
INSERT INTO operation VALUES("22","15000","2017-12-18 09:52:13","","CASH IN","1","1","66","15000","0","0","1","","SOPTSI PHILIPPE");
INSERT INTO operation VALUES("23","100","2017-12-18 09:52:13","","CASH IN","1","1","66","2550","0","0","0","141","SOPTSI PHILIPPE");
INSERT INTO operation VALUES("24","2500","2017-12-18 09:54:34","","CASH OUT","1","1","11","-2500","0","1","0","","SONWA JEAN");
INSERT INTO operation VALUES("25","2500","2017-12-18 09:54:34","","CASH OUT","1","1","11","459500","0","0","0","44","SONWA JEAN");
INSERT INTO operation VALUES("26","20000","2017-12-19 11:06:20","","CASH IN","1","1","115","20000","0","1","0","","azety");
INSERT INTO operation VALUES("27","15000","2017-12-19 11:06:21","","CASH IN","1","1","115","15000","1","0","0","","azety");
INSERT INTO operation VALUES("28","30000","2017-12-19 11:06:21","","CASH IN","1","1","115","30000","0","0","1","","azety");
INSERT INTO operation VALUES("29","100","2017-12-19 11:06:21","","CASH IN","1","1","115","2650","0","0","0","141","azety");
INSERT INTO operation VALUES("30","2000","2017-12-19 11:06:21","","CASH IN","1","1","115","22000","0","0","0","151","azety");
INSERT INTO operation VALUES("31","9000","2017-12-19 11:09:43","","CASH IN","1","1","","49000","0","0","0","38","");
INSERT INTO operation VALUES("32","25000","2017-12-19 11:13:00","","CASH OUT","1","1","5","201500","0","1","0","","TEDONGMO ROSTAND");
INSERT INTO operation VALUES("33","25000","2017-12-19 11:13:00","","CASH OUT","1","1","5","454500","0","0","0","44","TEDONGMO ROSTAND");



DROP TABLE report_item;

CREATE TABLE `report_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_item_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6C37C8E560272618` (`parent_item_id`),
  CONSTRAINT `FK_6C37C8E560272618` FOREIGN KEY (`parent_item_id`) REFERENCES `report_item` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO report_item VALUES("1","","INCOMES","0");
INSERT INTO report_item VALUES("2","","EXPENDITURES","0");
INSERT INTO report_item VALUES("3","1","SALE OF STATIONARY","1455");
INSERT INTO report_item VALUES("4","1","DAILY SAVINGS INCOME","0");
INSERT INTO report_item VALUES("5","1","LOAN PROCESSING FEES","6522");
INSERT INTO report_item VALUES("6","1","SAVINGS WITHDRAWAL CHARGES","0");
INSERT INTO report_item VALUES("7","1","MOBILE MONEY INCOME","0");
INSERT INTO report_item VALUES("8","1","ENTRANCE FEES","0");
INSERT INTO report_item VALUES("9","1","INCOME FROM ELECTRICITY","0");
INSERT INTO report_item VALUES("11","1","LOAN INTEREST","0");
INSERT INTO report_item VALUES("12","2","REPAIRS","0");
INSERT INTO report_item VALUES("13","2","TELECOMMUNICATION","6544");
INSERT INTO report_item VALUES("14","2","TYPING/PRINTING/PHOTOCOPING","0");
INSERT INTO report_item VALUES("15","2","TRANSPORT","0");
INSERT INTO report_item VALUES("16","2","DAILY SAVING EXPENSES","0");
INSERT INTO report_item VALUES("17","2","PERSONNEL EXPENSES","0");
INSERT INTO report_item VALUES("18","2","ELECTRICITY/WATER/OMO","0");
INSERT INTO report_item VALUES("19","2","OFFICE STATIONARIES","0");
INSERT INTO report_item VALUES("20","2","HOUSE RENT","0");



DROP TABLE transaction_income;

CREATE TABLE `transaction_income` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` bigint(20) NOT NULL,
  `transactionDate` datetime NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO transaction_income VALUES("1","100","2017-12-13 04:51:25","Operation charges. Account Number: 970000010035 // Amount: 100");
INSERT INTO transaction_income VALUES("2","5000","2017-12-13 11:48:25","Loan Interest payment. Loan Code: BL-10028 // Amount: 5000");
INSERT INTO transaction_income VALUES("3","0","2017-12-13 13:47:46","Operation charges. Account Number: 980000010001 // Amount: 6000");
INSERT INTO transaction_income VALUES("4","0","2017-12-13 13:49:57","Operation charges. Account Number: 980000010001 // Amount: 6000");
INSERT INTO transaction_income VALUES("5","100","2017-12-13 14:37:35","Operation charges. Account Number: 970000010035 // Amount: 100");
INSERT INTO transaction_income VALUES("6","0","2017-12-13 14:39:14","Operation charges. Account Number: 970000010035 // Amount: 0");
INSERT INTO transaction_income VALUES("7","173","2017-12-13 14:45:31","Operation charges. Account Number: 990000010001 // Amount: 2000");
INSERT INTO transaction_income VALUES("8","100","2017-12-15 07:44:34","Deposit Charges. Amount: 100");
INSERT INTO transaction_income VALUES("9","100","2017-12-15 09:59:05","Deposit Charges. Amount: 100");



DROP TABLE utilisateur;

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `nom` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `numeroTelephone` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `groupe_id` int(11) NOT NULL,
  `lastActivity` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1D1C63B392FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_1D1C63B3A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_1D1C63B36C6E55B5` (`nom`),
  UNIQUE KEY `UNIQ_1D1C63B34829F0B3` (`numeroTelephone`),
  UNIQUE KEY `UNIQ_1D1C63B3C05FB297` (`confirmation_token`),
  KEY `IDX_1D1C63B37A45358C` (`groupe_id`),
  CONSTRAINT `FK_1D1C63B37A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `groupe` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO utilisateur VALUES("1","elsha","elsha","elsha@gmail.com","elsha@gmail.com","1","","$2y$13$QeOlyf6F/HWtUBIcrMkC2eCUhLEgwzKsbVdWkCrxV2sh.66q8PgBG","2017-12-19 11:00:28","Uz7w1kXo2l3P-loURkHHL8fgVAArAnhjtHOGyZayUkA","","a:1:{i:0;s:18:\"ROLE_ADMINISTRATOR\";}","SOKEING HYDIL AICARD","675943505","1","2017-12-19 11:48:24");
INSERT INTO utilisateur VALUES("2","jusphine","jusphine","jusphine@gmail.com","jusphine@gmail.com","1","","$2y$13$6IV2RhxcdrnB8.MqP1yN4OqDTkb731D2eHAifQxH61dx5dYNHd9D6","2017-12-18 04:21:24","6jKnx7xBLhE59Dw2eRpngZMVsCp-1JOVcVGc1shrfXk","","a:1:{i:0;s:12:\"ROLE_MANAGER\";}","SAHNYUY JUSPHINE KIJIKA","//","2","2017-12-18 05:47:26");
INSERT INTO utilisateur VALUES("4","rene","rene","rene@gmail.com","rene@gmail.com","1","","$2y$13$HvcWSjQh3AoZMh/1lPEB8.3TgRZ2w12pTLIgAX3cGTI/Zd6YeYhEe","2017-12-04 09:48:08","A0ik0mgLUKxwJjtjxQm1IXvwtBrXF79-hVXYLPWdkxU","","a:1:{i:0;s:14:\"ROLE_COLLECTOR\";}","TANKFU RENE","///","3","2017-12-04 09:48:08");
INSERT INTO utilisateur VALUES("5","sonwa","sonwa","sonwa@gmail.com","sonwa@gmail.com","1","","$2y$13$CZwHWy3DeYoa3r5KUUu6Pu1ePM5JjBFSvybLUUhHVA/VcRKqcrnQ2","2017-12-04 10:18:34","Z1YhT8SRyDGkL96GzvE-X7LoQjm3y2ykhjOSYlmtVrU","","a:1:{i:0;s:14:\"ROLE_COLLECTOR\";}","SONWA","////","3","2017-12-04 10:25:45");
INSERT INTO utilisateur VALUES("6","flora","flora","flora@gmail.com","flora@gmail.com","1","","$2y$13$tgq/3XvfE5DdjXH9ZawXBegXDqVgUjgJe4fR0h5JIqCnUouKfPuRm","2017-12-19 04:00:37","hiCZcj5ooS7XFmhNJiQ6putDKUwkDbm-mHuYsaUFToI","","a:1:{i:0;s:11:\"ROLE_CASHER\";}","FLORA","/////","4","2017-12-19 04:00:38");



