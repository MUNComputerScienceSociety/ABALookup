-- PRAGMAs
PRAGMA foreign_keys = ON;

-- User
CREATE TABLE account(
	id                     TEXT    PRIMARY KEY,
	user_id                TEXT    UNIQUE      NOT NULL,
	password               TEXT                NOT NULL,
	password_reset_code    TEXT                NULL,
	email                  TEXT    UNIQUE      NOT NULL,
	email_confirmed        INTEGER             NOT NULL,
	email_confirm_code     TEXT                NULL,
	access_level           INTEGER             NOT NULL,
	terms_of_service       INTEGER             NOT NULL,
	creation_time          INTEGER             NOT NULL,
	-- Associations
	FOREIGN KEY(user_id)              REFERENCES user(id)              ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE user(
	id                     TEXT    PRIMARY KEY,
	user_display_name_id   INTEGER UNIQUE      NOT NULL,
	user_type_id           INTEGER             NOT NULL,
	location_id            INTEGER             NOT NULL,
	gender                 TEXT,
	phone_number           TEXT,
	aba_course             INTEGER,
	certificate_of_conduct INTEGER,
	creation_time          INTEGER             NOT NULL,
	-- Associations
	FOREIGN KEY(user_display_name_id) REFERENCES user_display_name(id) ON DELETE CASCADE,
	FOREIGN KEY(user_type_id)         REFERENCES user_type(id),
	FOREIGN KEY(location_id)          REFERENCES location(id)
);

CREATE TABLE user_display_name(
	id                     INTEGER PRIMARY KEY,
	user_id                TEXT                NULL,
	display_name           TEXT                NOT NULL,
	creation_time          INTEGER             NOT NULL,
	-- Associations
	FOREIGN KEY(user_id)              REFERENCES user(id)              ON DELETE CASCADE
);

CREATE TABLE user_type(
	id                     INTEGER PRIMARY KEY,
	name                   TEXT    UNIQUE
);

CREATE TABLE user_schedule(
	user_id                TEXT    UNIQUE      NOT NULL,
	schedule_id            TEXT    UNIQUE      NOT NULL,
	-- Associations
	FOREIGN KEY(user_id)              REFERENCES user(id)              ON DELETE CASCADE,
	FOREIGN KEY(schedule_id)          REFERENCES schedule(id)          ON DELETE CASCADE
);

-- Score
CREATE TABLE score(
	id                     INTEGER PRIMARY KEY,
	user_a_id              TEXT                NOT NULL,
	user_b_id              TEXT                NOT NULL,
	schedule_id            INTEGER             NOT NULL,
	score                  INTEGER             NOT NULL,
	-- Associations
	FOREIGN KEY(user_a_id)            REFERENCES user(id)              ON DELETE CASCADE,
	FOREIGN KEY(user_b_id)            REFERENCES user(id)              ON DELETE CASCADE,
	FOREIGN KEY(schedule_id)          REFERENCES schedule(id)          ON DELETE CASCADE
);

-- Schedule
CREATE TABLE schedule(
	id                     INTEGER PRIMARY KEY,
	name                   TEXT,
	enabled                INTEGER             NOT NULL
);

CREATE TABLE schedule_interval(
	id                     INTEGER PRIMARY KEY,
	schedule_id            INTEGER             NOT NULL,
	start_time             INTEGER             NOT NULL,
	end_time               INTEGER             NOT NULL,
	week_day               INTEGER             NOT NULL,
	-- Associations
	FOREIGN KEY(schedule_id)          REFERENCES schedule(id)          ON DELETE CASCADE
);

-- Location
CREATE TABLE location(
	id                     INTEGER PRIMARY KEY,
	city                   TEXT                NOT NULL,
	postal_code            TEXT                NOT NULL,
	CONSTRAINT city_postal_code_unique UNIQUE (city, postal_code)
);

-- Location Distance --
CREATE TABLE location_distance(
	location_a_id          INTEGER             NOT NULL,
	location_b_id          INTEGER             NOT NULL,
	distance               INTEGER             NOT NULL,
	-- Associations
	FOREIGN KEY(location_a_id)          REFERENCES location(id)        ON DELETE CASCADE,
	FOREIGN KEY(location_b_id)          REFERENCES location(id)        ON DELETE CASCADE
);
