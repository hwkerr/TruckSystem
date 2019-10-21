INSERT INTO Account VALUES
('test', 'silas.m@g.clemson.edu', '$2y$10$bmVBP7nTW8ARUOPWi8bq2.PS2k4VxQAsQvrSTgAV0nFCsGwgn2LY.', 'Test', 'Pant', x'89504E470D0A1A0A0000000D494844520000001000000010080200000090916836000000017352474200AECE1CE90000000467414D410000B18F0BFC6105000000097048597300000EC300000EC301C76FA8640000001E49444154384F6350DAE843126220493550F1A80662426C349406472801006AC91F1040F796BD0000000049454E44AE426082', true, false);
INSERT INTO Admin VALUES
('test');

INSERT INTO Account VALUES
('test2', 'hwkerr@clemson.edu', '$2y$10$bmVBP7nTW8ARUOPWi8bq2.PS2k4VxQAsQvrSTgAV0nFCsGwgn2LY.', 'The', 'Architect', x'89504E470D0A1A0A0000000D494844520000001000000010080200000090916836000000017352474200AECE1CE90000000467414D410000B18F0BFC6105000000097048597300000EC300000EC301C76FA8640000001E49444154384F6350DAE843126220493550F1A80662426C349406472801006AC91F1040F796BD0000000049454E44AE426082', true, false);
INSERT INTO Admin VALUES
('test2');

INSERT INTO Account VALUES
('test3', 'azering@clemson.edu', '$2y$10$bmVBP7nTW8ARUOPWi8bq2.PS2k4VxQAsQvrSTgAV0nFCsGwgn2LY.', 'Beef', 'Meringue', x'89504E470D0A1A0A0000000D494844520000001000000010080200000090916836000000017352474200AECE1CE90000000467414D410000B18F0BFC6105000000097048597300000EC300000EC301C76FA8640000001E49444154384F6350DAE843126220493550F1A80662426C349406472801006AC91F1040F796BD0000000049454E44AE426082', true, false);
INSERT INTO Admin VALUES
('test3');

INSERT INTO Company VALUES
('testcompany', 'test_company', x'89504E470D0A1A0A0000000D494844520000001000000010080200000090916836000000017352474200AECE1CE90000000467414D410000B18F0BFC6105000000097048597300000EC300000EC301C76FA8640000001E49444154384F6350DAE843126220493550F1A80662426C349406472801006AC91F1040F796BD0000000049454E44AE426082', false);

INSERT INTO Account VALUES
('testdriver', 'bigfallingboulders@gmail.com', '$2y$10$bmVBP7nTW8ARUOPWi8bq2.PS2k4VxQAsQvrSTgAV0nFCsGwgn2LY.', 'One', 'Driver', x'89504E470D0A1A0A0000000D494844520000001000000010080200000090916836000000017352474200AECE1CE90000000467414D410000B18F0BFC6105000000097048597300000EC300000EC301C76FA8640000001E49444154384F6350DAE843126220493550F1A80662426C349406472801006AC91F1040F796BD0000000049454E44AE426082', false, false);
INSERT INTO Driver VALUES
('testdriver', '29464', 'SC', 'M Pleasant', '520 Whilden Street', 'testcompany');

INSERT INTO Account VALUES
('testsponsor', 'big.falling.boulders@gmail.com', '$2y$10$bmVBP7nTW8ARUOPWi8bq2.PS2k4VxQAsQvrSTgAV0nFCsGwgn2LY.', 'One', 'Sponsor', x'89504E470D0A1A0A0000000D494844520000001000000010080200000090916836000000017352474200AECE1CE90000000467414D410000B18F0BFC6105000000097048597300000EC300000EC301C76FA8640000001E49444154384F6350DAE843126220493550F1A80662426C349406472801006AC91F1040F796BD0000000049454E44AE426082', false, false);
INSERT INTO Sponsor VALUES
('testsponsor', 'testcompany');

INSERT INTO CatalogItem VALUES
('testitem', 'ebay', 'ebay.com/thisisafakelink');
INSERT INTO Catalog VALUES
('testcatalog', 'Test Catalog', true, 'testcompany');
INSERT INTO CatalogCatalogItem VALUES
('testcatalog', 'testitem', 'Test Item for Catalog', 50, 8.3, false, '', false, x'');