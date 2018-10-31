CREATE TABLE Alumni(
  NUMBER(9) alumID,
  VARCHAR(20) name,
  VARCHAR(2) verified,
  VARCHAR(30) email
  VARCHAR(12) phone

);

CREATE TABLE Businesses(
  NUMBER(9) businessID,
  VARCHAR(30) businessName,
  VARCHAR(30) owner FOREIGN KEY,
  VARCHAR(30) businessPhone,
  VARCHAR(30) businessEmail,
  VARCHAR(30) address,
  VARCHAR(30) website,
  DATETIME CreatedDateTime


);

CREATE TABLE Verified(
  NUMBER(9) BusinessID FOREIGN KEY,
  VARCHAR(2) verified
);


Create sequence alumni_sequence start with 1
increment by 1
minvalue 1
maxvalue 10000;

Create Sequence businesses_sequence start with 1
increment by 1
minvalue 1
maxvalue 10000;
