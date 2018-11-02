DROP TABLE Automotive;
DROP TABLE Retail;
DROP TABLE Restaurant;
DROP TABLE Verified;
DROP TABLE Businesses;
DROP TABLE Alumni;
DROP SEQUENCE alumni_sequence;
DROP SEQUENCE businesses_sequence;
DROP PROCEDURE VERIFYbusiness;
DROP PROCEDURE DELETEbusiness;
DROP PROCEDURE INSERTpro;

CREATE TABLE Alumni(
  alumID NUMBER(5),
  name VARCHAR(30),
  --email VARCHAR(30),
  phone VARCHAR(30),
  CreatedDateTime TIMESTAMP,
  CONSTRAINT alumni_pk Primary Key(alumID)
);

CREATE TABLE Businesses(
  businessID NUMBER(5),
  businessName VARCHAR(30),
  alumID NUMBER(5),
  phoneNo VARCHAR(30),
  --email VARCHAR(30),
  city VARCHAR(30),
  type VARCHAR(30),
  --website VARCHAR(30),
  CreatedDateTime TIMESTAMP,
  CONSTRAINT businesses_pk Primary Key(businessID),
  CONSTRAINT businesses_fk Foreign Key(alumID) REFERENCES Alumni(alumID)
);

CREATE TABLE Verified(
  BusinessID NUMBER(5) UNIQUE,
  Foreign Key(BusinessID) REFERENCES Businesses(BusinessID)
);

-- CREATE TABLE Automotive(
--   BusinessID NUMBER(5),
--   Foreign Key(BusinessID) REFERENCES Businesses(BusinessID)
-- );
--
-- CREATE TABLE Restaurant(
--   businessID NUMBER(5),
--   Foreign Key(BusinessID) REFERENCES Businesses(BusinessID)
-- );
--
-- CREATE TABLE Retail(
--   businessID NUMBER(5),
--   Foreign Key(BusinessID) REFERENCES Businesses(BusinessID)
-- );

Create sequence alumni_sequence start with 1
increment by 1
minvalue 1
maxvalue 10000;

Create Sequence businesses_sequence start with 1
increment by 1
minvalue 1
maxvalue 10000;

CREATE OR REPLACE PROCEDURE DELETEbusiness (busID IN NUMBER)
AS
BEGIN
  DELETE FROM Businesses WHERE Businesses.businessID = busID;
END;
/
show errors;

CREATE OR REPLACE PROCEDURE VERIFYbusiness(businessID IN NUMBER)
AS
BEGIN
  INSERT INTO Verified VALUES (businessID);
END;
/
show errors;

CREATE OR REPLACE PROCEDURE INSERTpro(alumName IN VARCHAR, busName IN VARCHAR, city IN VARCHAR, phoneNo IN VARCHAR, busType IN VARCHAR)
AS
  alumID NUMBER(5):= alumni_sequence.nextval;
  businessID NUMBER(5) := businesses_sequence.nextval;
BEGIN
  INSERT INTO ALUMNI VALUES(alumID, alumName, phoneNo, CURRENT_TIMESTAMP);
  INSERT INTO BUSINESSES VALUES(businessID, busName, alumID, phoneNo, city, busType, CURRENT_TIMESTAMP);

  -- CASE busType
  --   WHEN 'Automotive' THEN (INSERT INTO Automotive VALUES(businessID))
  --   WHEN 'Restaurant' THEN (INSERT INTO Restaurant VALUES(businessID))
  --   WHEN 'Retail' THEN (INSERT INTO Retail VALUES(businessID))
  -- END CASE;
END;
/
show errors;

-- CREATE OR REPLACE FUNCTION SEARCHpro(busName IN VARCHAR, city in VARCHAR , busType IN VARCHAR)
-- AS
--
-- BEGIN
--   SELECT businessName, phoneNo FROM Businesses WHERE lower(busname) = lower(businesses.businessName) AND lower(city) = lower(businesses.city) AND lower(busType) = lower(businesses.type);
-- END;
-- /
-- show errors;

EXEC INSERTpro('Henry','Henrys Plumbing','Santa Clara', '(408)123-4567', 'Retail');
EXEC INSERTpro('Susie','Susies Bakery','Santa Clara', '(408)123-4567', 'Restaurant');

-- INSERT INTO ALUMNI VALUES (alumni_sequence.nextval, 'Henry', '(408)123-4567', CURRENT_TIMESTAMP);
-- INSERT INTO ALUMNI VALUES (alumni_sequence.nextval, 'Joe', '(408)890-1234', CURRENT_TIMESTAMP);
-- INSERT INTO ALUMNI VALUES (alumni_sequence.nextval, 'Susie', '(408)567-8901', CURRENT_TIMESTAMP);
-- INSERT INTO ALUMNI VALUES (alumni_sequence.nextval, 'Daisy', '(408)234-5678', CURRENT_TIMESTAMP);
--
-- INSERT INTO BUSINESSES VALUES (businesses_sequence.nextval, 'Henry\'s Plumbing',)
