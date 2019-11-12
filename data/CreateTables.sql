CREATE TABLE Account
(
    UserID            VARCHAR(16)            NOT NULL,
    Email            VARCHAR(50)            NOT NULL,
    PassHash        CHAR(60)            NOT NULL,
    FName            VARCHAR(20)            NOT NULL,
    LName            VARCHAR(20)            NOT NULL,
    Image            MEDIUMBLOB            NOT NULL,
    TempPass        BOOLEAN            NOT NULL,
    Deleted            BOOLEAN            NOT NULL,
    PRIMARY KEY (UserID)
);

CREATE TABLE Admin
(
    UserID            VARCHAR(16)            NOT NULL,
    PRIMARY KEY (UserID),
    FOREIGN KEY (UserID) REFERENCES Account (UserID)
);

CREATE TABLE Company
(
    CompanyID        VARCHAR(16)            NOT NULL,
    Name            VARCHAR(40)            NOT NULL,
    Image            MEDIUMBLOB            NOT NULL,
    Deleted            BOOLEAN            NOT NULL,
    DriverAd VARCHAR(500) NOT NULL,
    SponsorInfo VARCHAR(500) NOT NULL,
    PRIMARY KEY (CompanyID)
);

CREATE TABLE Sponsor
(
    UserID            VARCHAR(16)            NOT NULL,
    CompanyID        VARCHAR(16)            NOT NULL,
    PRIMARY KEY (UserID),
    FOREIGN KEY (UserID) REFERENCES Account (UserID),
    FOREIGN KEY (CompanyID) REFERENCES Company (CompanyID)
);

CREATE TABLE Driver
(
    UserID            VARCHAR(16)            NOT NULL,
    Zip            VARCHAR(5)            NOT NULL,
    State            VARCHAR(2)            NOT NULL,
    City            VARCHAR(10)            NOT NULL,
    Street            VARCHAR(30)            NOT NULL,
    CurrComp VARCHAR(16) NOT NULL,
	Phone		VARCHAR(10)		NOT NULL,
	Street2		VARCHAR(30)		NOT NULL,
	PointAlert	BOOLEAN		NOT NULL,
	OrderAlert	BOOLEAN		NOT NULL,
	ChangeAlert	BOOLEAN		NOT NULL,
    PRIMARY KEY (UserID),
    FOREIGN KEY (UserID) REFERENCES Account (UserID)
);

CREATE TABLE DriverCompany
(
    DriverID VARCHAR(16) NOT NULL,
    CompanyID VARCHAR(16) NOT NULL,
    Accepted BOOLEAN NOT NULL,
    PRIMARY KEY (DriverID, CompanyID),
    FOREIGN KEY (DriverID) REFERENCES Driver (UserID),
    FOREIGN KEY (CompanyID) REFERENCES Company (CompanyID)
);

CREATE TABLE PointAddition
(
    AdditionID        VARCHAR(16)            NOT NULL,
    Amount        INT                NOT NULL,
    Timestamp        DATETIME            NOT NULL,
    SponsorID        VARCHAR(16)            NOT NULL,
    DriverID        VARCHAR(16)            NOT NULL,
    PRIMARY KEY (AdditionID),
    FOREIGN KEY (SponsorID) REFERENCES Sponsor (UserID),
    FOREIGN KEY (DriverID) REFERENCES Driver (UserID)
);

CREATE TABLE ItemOrder
(
    OrderID            VARCHAR(16)            NOT NULL,
    Timestamp        DATETIME            NOT NULL,
    Deleted            BOOLEAN            NOT NULL,
    DriverID        VARCHAR(16)            NOT NULL,
    PRIMARY KEY (OrderID),
    FOREIGN KEY (DriverID) REFERENCES Driver (UserID)
);

CREATE TABLE Catalog
(
    CatalogID        VARCHAR(16)            NOT NULL,
    Name            VARCHAR(20)            NOT NULL,
    Visible            BOOLEAN            NOT NULL,
    CompanyID        VARCHAR(16)            NOT NULL,
    PRIMARY KEY (CatalogID),
    FOREIGN KEY (CompanyID) REFERENCES Company (CompanyID)
);

CREATE TABLE CatalogItem
(
    ItemID            VARCHAR(16)            NOT NULL,
    WebSource        VARCHAR(20)            NOT NULL,
    LinkInfo            VARCHAR(100)            NOT NULL,
    PRIMARY KEY (ItemID)
);

CREATE TABLE ItemOrderCatalogItem
(
    OrderID            VARCHAR(16)            NOT NULL,
    ItemID            VARCHAR(16)            NOT NULL,
    PointPrice        INT                NOT NULL,
    Position            INT                NOT NULL,
    Shipped        BOOLEAN            NOT NULL,
    Cancelled        BOOLEAN            NOT NULL,
    PRIMARY KEY (OrderID, ItemID),
    FOREIGN KEY (OrderID) REFERENCES ItemOrder (OrderID),
    FOREIGN KEY (ItemID) REFERENCES CatalogItem (ItemID)
);

CREATE TABLE CatalogCatalogItem
(
    CatalogID        VARCHAR(16)            NOT NULL,
    ItemID            VARCHAR(16)            NOT NULL,
    Name            VARCHAR(40)            NOT NULL,
    PointPrice        INT                NOT NULL,
    Discount        DECIMAL(3, 2)            NOT NULL,
    CustomDesc        BOOLEAN            NOT NULL,
    Description        VARCHAR(256)            NULL,
    CustomImg        BOOLEAN            NOT NULL,
    Image            MEDIUMBLOB            NULL,
    PRIMARY KEY (CatalogID, ItemID),
    FOREIGN KEY (CatalogID) REFERENCES Catalog (CatalogID),
    FOREIGN KEY (ItemID) REFERENCES CatalogItem (ItemID)
);

CREATE TABLE Application
(
    AppID VARCHAR(16) NOT NULL,
    FName VARCHAR(20) NOT NULL,
    LName VARCHAR(20) NOT NULL,
    Email VARCHAR(50) NOT NULL,
    Info VARCHAR(500) NOT NULL,
    UserType VARCHAR(20) NOT NULL,
    Processed BOOLEAN NOT NULL,
    PRIMARY KEY (AppID)
);
