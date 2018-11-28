DROP TABLE Verified;
DROP TABLE Addresses;
DROP TABLE Businesses;
DROP TABLE Alumni;
DROP TABLE Creds;
DROP SEQUENCE alumni_sequence;
DROP SEQUENCE businesses_sequence;
DROP PROCEDURE VERIFYbusiness;
DROP PROCEDURE DELETEListing;
DROP PROCEDURE INSERTpro;

CREATE TABLE Creds(
    username VARCHAR(30),
    pass VARCHAR(30)
);


CREATE TABLE Alumni(
  alumID NUMBER(5),
  name VARCHAR(30),
  phone VARCHAR(30),
  email VARCHAR(30),
  gradYear VARCHAR(4),
  securityQuestion VARCHAR(40),
  questionAns VARCHAR(40),
  CreatedDateTime TIMESTAMP,
  CONSTRAINT alumni_pk Primary Key(alumID)
);

CREATE TABLE Businesses(
  businessID NUMBER(5),
  alumID NUMBER(5),
  type VARCHAR(30),
  businessName VARCHAR(30),
  phoneNo VARCHAR(30),
  email VARCHAR(30),
  description VARCHAR(4000),
  website VARCHAR(30),
  --photo BLOB,
  CreatedDateTime TIMESTAMP,
  CONSTRAINT businesses_pk Primary Key(businessID),
  CONSTRAINT businesses_fk Foreign Key(alumID) REFERENCES Alumni(alumID)
);

CREATE TABLE Addresses(
    businessID NUMBER(5),
    addressLine1 VARCHAR(30),
    addressLine2 VARCHAR(30),
    city VARCHAR(30),
    zipcode VARCHAR(10),
    state VARCHAR(30),
    country VARCHAR(30),
    CONSTRAINT address_fk Foreign Key(businessID) REFERENCES Businesses(businessID)
);

CREATE TABLE Verified(
  businessID NUMBER(5) UNIQUE,
  alumID NUMBER(5),
  Foreign Key(businessID) REFERENCES Businesses(BusinessID),
  Foreign Key(alumID) REFERENCES Alumni(alumID)
);

Create sequence alumni_sequence start with 1
increment by 1
minvalue 1
maxvalue 10000;

Create Sequence businesses_sequence start with 1
increment by 1
minvalue 1
maxvalue 10000;

CREATE OR REPLACE PROCEDURE DELETElisting (busID IN NUMBER)
AS
BEGIN
  DELETE FROM Businesses WHERE Businesses.businessID = busID;
END;
/
show errors;

CREATE OR REPLACE PROCEDURE VERIFYbusiness(businessID IN NUMBER, alumID IN NUMBER)
AS
BEGIN
  INSERT INTO Verified VALUES (businessID, alumID);
END;
/
show errors;

CREATE OR REPLACE PROCEDURE INSERTpro(alumName IN VARCHAR, alumPhone in VARCHAR, alumEmail in VARCHAR, gradYear in VARCHAR, securityQuestion in VARCHAR, answer in VARCHAR, busType in VARCHAR, busName IN VARCHAR, busPhone in VARCHAR, busEmail in VARCHAR, addressL1 in VARCHAR, addressL2 in Varchar, city IN VARCHAR, zipCode in VARCHAR, state in VARCHAR, country in VARCHAR, description in VARCHAR, website in VARCHAR/*, image in BLOB*/)
AS
  alumID NUMBER(5):= alumni_sequence.nextval;
  newBusinessID NUMBER(5) := businesses_sequence.nextval;
BEGIN
  INSERT INTO ALUMNI VALUES(alumID, alumName, alumPhone, alumEmail, gradYear,securityQuestion, answer, CURRENT_TIMESTAMP);
  INSERT INTO BUSINESSES VALUES(newBusinessID, alumID, busType, busName, busPhone, busEmail, description, website, /*empty_blob(),*/CURRENT_TIMESTAMP);
  INSERT INTO ADDRESSES VALUES(newBusinessID, addressL1, addressL2, city, zipCode, state, country);
  --SELECT image INTO photo from Businesses where newBusinessID = businessID for UPDATE;

 -- image.setProperties();


END;
/
show errors;

EXEC INSERTpro('Henry Moore', '1234567', 'hmoore@scu.edu', '1997', 'This is my question', 'This is my answer', 'Retail', 'My Retail', '1234567', 'mybiz@gmail.com', '123 Mystreet', 'Suite 1',  'Santa Clara', '95053', 'CA', 'USA', 'This is a description', 'mysite.com');

-- CREATE OR REPLACE FUNCTION SEARCHpro(busName IN VARCHAR, city in VARCHAR , busType IN VARCHAR)
-- AS
--
-- BEGIN
--   SELECT businessName, phoneNo FROM Businesses WHERE lower(busname) = lower(businesses.businessName) AND lower(city) = lower(businesses.city) AND lower(busType) = lower(businesses.type);
-- END;
-- /
-- show errors;

--EXEC INSERTpro('Henry','Henrys Plumbing','Santa Clara', '(408)123-4567', 'Retail');
--EXEC INSERTpro('Susie','Susies Bakery','Santa Clara', '(408)123-4567', 'Restaurant');

-- INSERT INTO ALUMNI VALUES (alumni_sequence.nextval, 'Henry', '(408)123-4567', CURRENT_TIMESTAMP);
-- INSERT INTO ALUMNI VALUES (alumni_sequence.nextval, 'Joe', '(408)890-1234', CURRENT_TIMESTAMP);
-- INSERT INTO ALUMNI VALUES (alumni_sequence.nextval, 'Susie', '(408)567-8901', CURRENT_TIMESTAMP);
-- INSERT INTO ALUMNI VALUES (alumni_sequence.nextval, 'Daisy', '(408)234-5678', CURRENT_TIMESTAMP);
--
-- INSERT INTO BUSINESSES VALUES (businesses_sequence.nextval, 'Henry\'s Plumbing',)
