DROP DATABASE IF EXISTS `vroh-matches`;

CREATE DATABASE `vroh-matches`;

USE `vroh-matches`;

CREATE TABLE Users (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL
);

CREATE TABLE Tournaments (
    TournamentID INT PRIMARY KEY AUTO_INCREMENT,
    OrganizerID INT,
    TournamentName VARCHAR(255) NOT NULL,
    Format VARCHAR(255) NOT NULL,
    StartDate DATE,
    EndDate DATE,
    FOREIGN KEY (OrganizerID) REFERENCES Users(UserID)
);

CREATE TABLE Participants (
    ParticipantID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT,
    TournamentID INT,
    GroupNumber INT,  -- Added for the group stage
    PositionInGroup INT,  -- 1st or 2nd place in the group
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    FOREIGN KEY (TournamentID) REFERENCES Tournaments(TournamentID)
);

CREATE TABLE Matches (
    MatchID INT PRIMARY KEY AUTO_INCREMENT,
    TournamentID INT,
    Round INT,
    Player1ID INT,
    Player2ID INT,
    WinnerID INT,
    FOREIGN KEY (TournamentID) REFERENCES Tournaments(TournamentID),
    FOREIGN KEY (Player1ID) REFERENCES Participants(ParticipantID),
    FOREIGN KEY (Player2ID) REFERENCES Participants(ParticipantID),
    FOREIGN KEY (WinnerID) REFERENCES Participants(ParticipantID)
);
