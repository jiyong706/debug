
-- Create the messages table
CREATE TABLE messages (
  msg_id NUMBER(11) NOT NULL,
  incoming_msg_id NUMBER(255) NOT NULL,
  outgoing_msg_id NUMBER(255) NOT NULL,
  msg VARCHAR2(1000) NOT NULL,
  CONSTRAINT messages_pk PRIMARY KEY (msg_id)
);

-- Create the users table
CREATE TABLE users (
  user_id NUMBER(11) NOT NULL,
  unique_id NUMBER(255) NOT NULL,
  fname VARCHAR2(255) NOT NULL,
  lname VARCHAR2(255) NOT NULL,
  email VARCHAR2(255) NOT NULL,
  password VARCHAR2(255) NOT NULL,
  img VARCHAR2(255) NOT NULL,
  status VARCHAR2(255) NOT NULL,
  CONSTRAINT users_pk PRIMARY KEY (user_id)
);

-- Create sequence for auto-increment functionality
CREATE SEQUENCE messages_seq START WITH 1 INCREMENT BY 1;

-- Create trigger for auto-increment functionality
CREATE OR REPLACE TRIGGER messages_trigger
BEFORE INSERT ON messages
FOR EACH ROW
BEGIN
  :NEW.msg_id := messages_seq.NEXTVAL;
END;
/

-- Create index for messages table
CREATE INDEX messages_outgoing_idx ON messages (outgoing_msg_id);

-- Create index for users table
CREATE INDEX users_unique_idx ON users (unique_id);
