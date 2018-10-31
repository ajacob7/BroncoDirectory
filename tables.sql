CREATE TABLE Alumni(
  NUMBER(9) alumID Primary Key,
  VARCHAR(20) name,
  VARCHAR(30) email
  VARCHAR(12) phone
  DATETIME CreatedDateTime

);

CREATE TABLE Businesses(
  NUMBER(9) businessID Primary Key,
  VARCHAR(30) businessName,
  VARCHAR(30) owner FOREIGN KEY,
  VARCHAR(30) businessPhone,
  VARCHAR(30) businessEmail,
  VARCHAR(30) address,
  VARCHAR(30) website,
  DATETIME CreatedDateTime


);

CREATE TABLE Verified(
  NUMBER(9) BusinessID,
  NUMBER(1) verified,
  Foreign Key(BusinessID) REFERENCES Businesses
);


Create sequence alumni_sequence start with 1
increment by 1
minvalue 1
maxvalue 10000;

Create Sequence businesses_sequence start with 1
increment by 1
minvalue 1
maxvalue 10000;
