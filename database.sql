CREATE DATABASE IF NOT EXISTS online_exam CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE online_exam;

CREATE TABLE organizations (
  OrganizationID INT AUTO_INCREMENT PRIMARY KEY,
  OrganizationName VARCHAR(150) NOT NULL,
  Type VARCHAR(80) NOT NULL,
  Email VARCHAR(150),
  ContactNumber VARCHAR(30)
);

CREATE TABLE candidates (
  CandidateID INT AUTO_INCREMENT PRIMARY KEY,
  FullName VARCHAR(150) NOT NULL,
  NIC VARCHAR(30),
  Email VARCHAR(150) NOT NULL UNIQUE,
  Phone VARCHAR(30),
  Address VARCHAR(255),
  DateOfBirth DATE,
  Username VARCHAR(80) NOT NULL UNIQUE,
  Password VARCHAR(255) NOT NULL,
  CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
  AdminID INT AUTO_INCREMENT PRIMARY KEY,
  Name VARCHAR(150) NOT NULL,
  Email VARCHAR(150),
  Username VARCHAR(80) NOT NULL UNIQUE,
  Password VARCHAR(255) NOT NULL
);

CREATE TABLE exams (
  ExamID INT AUTO_INCREMENT PRIMARY KEY,
  ExamTitle VARCHAR(200) NOT NULL,
  ExamType VARCHAR(100) NOT NULL,
  Date DATETIME NOT NULL,
  Duration INT NOT NULL,
  TotalMarks INT NOT NULL DEFAULT 0,
  OrganizationID INT,
  FOREIGN KEY (OrganizationID) REFERENCES organizations(OrganizationID) ON DELETE SET NULL
);

CREATE TABLE questions (
  QuestionID INT AUTO_INCREMENT PRIMARY KEY,
  ExamID INT NOT NULL,
  QuestionText TEXT NOT NULL,
  OptionA VARCHAR(500) NOT NULL,
  OptionB VARCHAR(500) NOT NULL,
  OptionC VARCHAR(500) NOT NULL,
  OptionD VARCHAR(500) NOT NULL,
  CorrectAnswer ENUM('A','B','C','D') NOT NULL,
  Marks INT NOT NULL DEFAULT 1,
  FOREIGN KEY (ExamID) REFERENCES exams(ExamID) ON DELETE CASCADE
);

CREATE TABLE applications (
  ApplicationID INT AUTO_INCREMENT PRIMARY KEY,
  CandidateID INT NOT NULL,
  ExamID INT NOT NULL,
  ApplyDate DATETIME DEFAULT CURRENT_TIMESTAMP,
  Status VARCHAR(40) DEFAULT 'Approved',
  UNIQUE KEY unique_application (CandidateID, ExamID),
  FOREIGN KEY (CandidateID) REFERENCES candidates(CandidateID) ON DELETE CASCADE,
  FOREIGN KEY (ExamID) REFERENCES exams(ExamID) ON DELETE CASCADE
);

CREATE TABLE payments (
  PaymentID INT AUTO_INCREMENT PRIMARY KEY,
  ApplicationID INT NOT NULL,
  Amount DECIMAL(10,2) DEFAULT 0,
  PaymentMethod VARCHAR(50),
  PaymentDate DATETIME DEFAULT CURRENT_TIMESTAMP,
  PaymentStatus VARCHAR(40) DEFAULT 'Pending',
  FOREIGN KEY (ApplicationID) REFERENCES applications(ApplicationID) ON DELETE CASCADE
);

CREATE TABLE results (
  ResultID INT AUTO_INCREMENT PRIMARY KEY,
  CandidateID INT NOT NULL,
  ExamID INT NOT NULL,
  MarksObtained INT NOT NULL,
  Grade VARCHAR(10) NOT NULL,
  Status VARCHAR(40) DEFAULT 'Completed',
  ResultDate DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (CandidateID) REFERENCES candidates(CandidateID) ON DELETE CASCADE,
  FOREIGN KEY (ExamID) REFERENCES exams(ExamID) ON DELETE CASCADE
);

INSERT INTO organizations (OrganizationName,Type,Email,ContactNumber)
VALUES ('Demo Examination Organization','Private Institution','admin@example.com','0110000000');

INSERT INTO admins (Name,Email,Username,Password)
VALUES ('System Administrator','admin@example.com','admin',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4x2bR6W6l5Y6nq9K6QpZ0n9YfH5X0eK');

INSERT INTO exams (ExamTitle,ExamType,Date,Duration,TotalMarks,OrganizationID)
VALUES ('Diploma ICT Mock Examination','Academic',DATE_ADD(NOW(), INTERVAL 2 DAY),30,10,1);

INSERT INTO questions (ExamID,QuestionText,OptionA,OptionB,OptionC,OptionD,CorrectAnswer,Marks) VALUES
(1,'Which device is commonly used to connect a computer to a network?','Router','Keyboard','Printer','Scanner','A',1),
(1,'What does SQL stand for?','Structured Query Language','Simple Query Language','System Query Logic','Sequential Question Language','A',1),
(1,'Which language is used for styling web pages?','PHP','SQL','CSS','Java','C',1),
(1,'Which HTML element is used for the main heading?','<p>','<h1>','<div>','<head>','B',1),
(1,'Which technology is used for server-side scripting in this project?','PHP','CSS','HTML','Bootstrap','A',1),
(1,'Which database is used by this project?','MySQL','MongoDB','SQLite','Oracle','A',1),
(1,'Which feature saves candidate answers periodically?','Auto-save','Auto-delete','Auto-print','Auto-close','A',1),
(1,'What is 2FA mainly used for?','Authentication security','Image editing','Database backup','Styling','A',1),
(1,'Which framework is included for responsive UI components?','Bootstrap','Laravel','Django','Spring','A',1),
(1,'Which utility CSS framework is included?','Tailwind CSS','Vue','React','Node.js','A',1);

-- Demo candidate password is: password
INSERT INTO candidates (FullName,NIC,Email,Phone,Address,DateOfBirth,Username,Password)
VALUES ('Demo Candidate','200000000000','candidate@example.com','0770000000','Sri Lanka','2005-01-01','candidate',
'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4x2bR6W6l5Y6nq9K6QpZ0n9YfH5X0eK');
